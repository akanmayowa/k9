<?php

namespace App\Services;

use App\Bag;
use App\Site;
use App\User;
use Exception;
use App\Transfer;
use Carbon\Carbon;
use App\Enums\BagType;
use App\Enums\BagStatus;
use App\Enums\TransferStatus;
use App\Events\BagTransfered;
use Doctrine\DBAL\Schema\Index;
use App\Exceptions\BagException;
use Illuminate\Support\Facades\DB;
use App\Events\TransferAcknowledged;
use App\Exceptions\TransferException;
use App\Events\TransferPartiallyAcknowledged;
use App\TransferBag;

class BagServices
{


    public function registerAutoNumbering($data)
    {
        $type = $data['bag_type'];
        $type_last_bag = Bag::where('type', $type)->orderBy('number', 'desc')->first();
        $last_number = 0;
        if($type_last_bag != null)
        {
            $last_number = $type_last_bag->number;
        }
        $number_of_bags =  $data['number_of_bags'];
        if($number_of_bags < 1)
        {
            throw new BagException("Please enter the number of bags to create");
        }


        $current_site_id = $data['site_id'];
        $created_by = $data['created_by'];
        $status = BagStatus::AVAILABLE_FOR_USE; // default status on Registration

        $bags = [];

        for($index = $last_number+1; $index <= ($last_number + $number_of_bags); ++$index)
        {
            $padded_number =  str_pad($index, 4, '0', STR_PAD_LEFT);
            $bags[] = [
                        'id' => $type . '-'. $padded_number,
                        'type' => $type,
                        'number' => $index,
                        'status' => $status,
                        'next_or_current_site_id' => $current_site_id,
                        'created_by' =>  $created_by,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()];
        }



        var_dump($bags);

        Bag::insert($bags);

        echo "successful..\n";
    }

    public function dispatch($data)
    {
        //----Start Argument valiadation
        if (!array_key_exists('bag_id', $data) || empty($data['bag_id'])) {

            throw new BagException("bag id is required");
        }

        if (!array_key_exists('current_manifest_or_transfer_id', $data) || empty($data['current_manifest_or_transfer_id'])) {

            throw new BagException("Manifest id is required");
        }


        //departure site
        if (!array_key_exists('departure_site', $data) || $data['departure_site'] == null) {

            throw new BagException("departure site is required");
        }

        //destination site
        if (!array_key_exists('destination_site', $data) || $data['destination_site'] == null) {

            throw new BagException("destination site is required");
        }


        if (!array_key_exists('user', $data) || $data['user'] == null) {

            throw new BagException("user is required");
        }


        //Ends Argument validation

        $bag = Bag::find($data['bag_id']);
        if($bag == null)
        {
            throw new BagException("Bag not found");
        }

        $destination_site = $data['destination_site'];

        $departure_site  =$data['departure_site'];


        if($bag->status != BagStatus::AVAILABLE_FOR_USE)
        {
            throw new BagException("Bag ( $bag->displayId ) is not available for use / tranfer");
        }

        //Administrator unkor ????
        if($data['user']->site_id !== $bag->next_or_current_site_id)
        {
            throw new BagException("You are not in a site that can use/transfer this Bag ( $bag->displayId )");
        }



        if ($bag->next_or_current_site_id === $destination_site->id) {
            throw new BagException("Bag ( $bag->displayId ) is already in the destination site");
        }

        if ($departure_site->id === $destination_site->id) {
            throw new BagException("departure site and destination site for bag dispatch cannot be the the same");
        }

        //changes the location of bag
        $bag->status = BagStatus::IN_USE;
        $bag->next_or_current_site_id = $destination_site->id;
        $bag->current_manifest_or_transfer_id = $data['current_manifest_or_transfer_id'];
        $bag->updated_by = $data['user']->id;
        $bag->updated_at = now();
        return $bag->save();

    }

    public function acknowledgeDispatch($data)
    {
        if (!array_key_exists('bag_id', $data) || $data['bag_id'] == null) {

            throw new BagException("bag id is required");
        }


        if (!array_key_exists('user', $data) || $data['user'] == null) {

            throw new BagException("user is required");
        }

        $bag = Bag::find($data['bag_id']);
        if($bag == null)
        {
            throw new BagException("Bag not found");
        }

        if($bag->status != BagStatus::IN_USE)
        {
            throw new BagException("Bag is not in use");
        }

        // if($data['user']->site_id !== $bag->next_or_current_site_id)
        // {
        //     throw new BagException("You are not in a site that can receive this bag");
        // }

        $bag->status = BagStatus::AVAILABLE_FOR_USE;
        $bag->current_manifest_or_transfer_id =null;
        $bag->updated_by = $data['user']->id;
        $bag->updated_at = now();
        return $bag->save();
    }

    function transfer($data)
    {
        try {

        if (!array_key_exists('bag_numbers', $data) || $data['bag_numbers'] == null) {
            throw new BagException("bag_numbers param is required");
        }

        if (!array_key_exists('destination_site_id', $data) || $data['destination_site_id'] == null) {

            throw new BagException("destination site id param is required");
        }

        if (!array_key_exists('user', $data) || $data['user'] == null) {

            throw new BagException("user param is required");
        }



        $user = $data['user'];

        $departure_site = Site::find($user->site_id);


        if ($departure_site === null) {
            throw new BagException("Invalid departure site supplied!");
        }


        $destination_site = Site::find($data['destination_site_id']);
        if ($destination_site === null) {
            throw new BagException("Invalid destination site supplied!");
        }

        if ($destination_site->id === $user->site_id) {
            throw new BagException("same site transfer is not allowed");
        }



        // $separator = PHP_EOL;
        // // explode($separator, );
        $bags_numbers = preg_split("/\\r\\n|\\r|\\n/", $data['bag_numbers']);
        // dd($bags_numbers);
        $bags_retrieved = Bag::WhereIn('id', $bags_numbers)->get();
        // dd($bags_retrieved->pluck('id'));
        if ($bags_retrieved->isEmpty()) {
            throw new BagException("None of the bag numbers provided were found in our DB");
        }

        $bags = [];
        //Can't this loop be merged with the other one ?
        $non_existing_bags =  collect($bags_numbers)->diff($bags_retrieved->pluck('id'));

        if(!$non_existing_bags->isEmpty())
        {
            $non_existing_bags_as_string = $non_existing_bags->implode(',');
            throw new BagException("Bag $non_existing_bags_as_string could not be found");
        }

        foreach($bags_retrieved as $bag)
        {

            if($bag->status != BagStatus::AVAILABLE_FOR_USE)
            {
                throw new BagException("$bag->id is not available for transfer");
            }

            if($bag->next_or_current_site_id != $departure_site->id)
            {
                throw new BagException("$bag->id is not available for transfer in your site, $departure_site->id, $bag->next_or_current_site_id, $bag->status");
            }

            if($bag->next_or_current_site_id == $destination_site->id)
            {
                throw new BagException("$bag->id is already in $destination_site->name");
            }


                $bags[] = [
                'bag_id' => $bag->id,
                'departure_site_id' => $departure_site->id,
                'destination_site_id' => $destination_site->id,
                'created_by' => $user->id,
                'status' => BagStatus::ON_TRANSFER,
            ];
        }

     //-------------------TRANSACTION BEGINS-------------------
     DB::beginTransaction();
        $transfer =  \App\Transfer::create(
            [
                'departure_site_id' => $departure_site->id,
                'destination_site_id' => $destination_site->id,
                'status' => TransferStatus::IN_TRANSIT, // LOCKED,
                'acknowledged_by' => null,
                'acknowledged_at' => null,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_by' => null, //why not make the column null
                // 'updated_at' => now(),
            ]
        );

        Bag::whereIn('id', $bags_retrieved->pluck('id'))
        ->where('status', BagStatus::AVAILABLE_FOR_USE)
        ->update([
            'status' => BagStatus::ON_TRANSFER,
            'current_manifest_or_transfer_id' => $transfer->id,
            'next_or_current_site_id' => $destination_site->id,
            'updated_at' => now(),
            'updated_by' => $user->id,
        ]);

            $transfer->transfer_bags()->createMany(
                $bags
            );

       // ---------------------TRANSACTION ENDS-------------
        DB::commit();
        return ['success' => true, 'transfer' => $transfer, 'message' => 'success'];
    }
    catch (\Exception $ex) {
        //should I handle PDOException error or Exception ?
        DB::rollBack();

        return ['success' => false, 'transfer' => null, 'message' =>  $ex->getMessage()];
    }

    }
    public function acknowledgeTransfer($data)
    {
        try {

            $user = $data['user'];
            $transfer_id = $data['transfer_id'];

            $transfer = Transfer::with('transfer_bags:bag_id', 'departure_site', 'destination_site')->find($transfer_id);

            if ($transfer === null) {
                throw new TransferException("Oops, could not find transfer");
            }

            if ($transfer->status != TransferStatus::IN_TRANSIT && $transfer->status != TransferStatus::PARTIALLY_RECEIVED) {
                throw new TransferException("You can not acknowledge  a transfer with " . strtoupper(TransferStatus::STATUS_TEXT[$transfer->status]) . " status");
            }

            if ($transfer->destination_site_id != $user->site_id) {
                throw new TransferException("You cannot not acknowledge this transfer because it was not dispatched to your site");
            }

            DB::beginTransaction();

            TransferBag::where('transfer_id', $transfer_id)
                ->where('status', BagStatus::ON_TRANSFER) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => BagStatus::AVAILABLE_FOR_USE,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknowledged_by' => $user->id,
                    'acknowledged_at' => now()
                ]);

                Bag::where('current_manifest_or_transfer_id', $transfer->id)
                ->where('status', BagStatus::ON_TRANSFER) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => BagStatus::AVAILABLE_FOR_USE,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'current_manifest_or_transfer_id' => null
                ]);


            Transfer::where('id', $data['transfer_id'])->update(['status' => TransferStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);

            DB::commit();


            $context = compact('transfer', $transfer->transfer_bags, 'user');
            TransferAcknowledged::dispatch($context);
            return ['success' => true, 'transfer' => $transfer, 'message' => 'Transfer Acknowleged Successfully'];

        } catch (Exception $ex) {

            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'transfer' => null, 'message' => $ex->getMessage()];
        }
    }
    //Partial acknowledgement of bag is not supported
    public function acknowledgeTransferV2($data)
    {
        try {

            $user = $data['user'];
            $transfer_id = $data['transfer_id'];
            $bags =  $data['bags'];
            // dd($waybills);
            $transfer = Transfer::with('bags', 'departure_site', 'destination_site')->find($transfer_id);

            if ($transfer === null) {
                throw new TransferException("Oops, could not find transfer");
            }

            // if ($transfer->status != TransferStatus::IN_TRANSIT && $transfer->status != TransferStatus::PARTIALLY_RECEIVED) {
            //     throw new TransferException("You can not acknowledge  a transfer with " . strtoupper(TransferStatus::STATUS_TEXT[$transfer->status]) . " status");
            // }
            if ($transfer->status != TransferStatus::IN_TRANSIT && $transfer->status != TransferStatus::PARTIALLY_RECEIVED) {
                throw new TransferException("You can not acknowledge  a transfer with " . strtoupper(TransferStatus::STATUS_TEXT[$transfer->status]) . " status");
            }

            if ($transfer->destination_site_id != $user->site_id) {
                throw new TransferException("You cannot not acknowledge this transfer because it was not dispatched to your site");
            }

            if (collect($bags)->isEmpty()) {
                throw new TransferException("This transfer do not have any bag");
            }

            $bags_to_store = $bags;

            DB::beginTransaction();

			//Remember this is not K9 arrival
            $total_acknowledged_bag_count = collect($bags_to_store)->count();
            Bag::whereIn('id', $bags_to_store)
                ->where('transfer_id', $transfer_id)
                ->where('status', BagStatus::ON_TRANSFER) // or status equals Pre acknwoleged orhwere ManifestStatus::PARTIALLY_RECEIVED
                ->update([
                    'status' => BagStatus::AVAILABLE_FOR_USE,
                    'updated_at' => now(),
                    'updated_by' => $user->id,
                    'acknowledged_by' => $user->id,
                    'acknowledged_at' => now()
                ]);

            //Add acknwoledgement date acknowledged_at
            if ($total_acknowledged_bag_count < count($transfer->bags)) {

                Transfer::where('id', $data['transfer_id'])->update(['status' => TransferStatus::PARTIALLY_RECEIVED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
                //Bags which are not acknowledged to pending
            } else {

                Transfer::where('id', $data['transfer_id'])->update(['status' => TransferStatus::ACKNOWLEDGED, 'updated_at' => now(), 'acknowledged_by' => $user->id]);
            }

            DB::commit();


            $context = compact('manifest', 'bags', 'user');

            if ($total_acknowledged_bag_count < count($transfer->bags)) {

                //Announce it
                TransferPartiallyAcknowledged::dispatch($context);

                return ['success' => true, 'transfer' => $transfer, 'message' => 'Transfer PARTIALLY Acknowleged Successfully'];
            } else {
                //Annouce it
                TransferAcknowledged::dispatch($context);

                //you might want to replace manifest with manifest_id
                return ['success' => true, 'transfer' => $transfer, 'message' => 'Transfer Acknowleged Successfully'];
            }
        } catch (Exception $ex) {

            DB::rollBack();

            //Todo rethrow the Manifest Exception ?
            return ['success' => false, 'transfer' => null, 'message' => $ex->getMessage()];
        }
    }

    public function getBags($filters)
    {

        if (!array_key_exists('next_or_current_site_id', $filters) || $filters['next_or_current_site_id'] == null) {

            throw new BagException("next_or_current_site_id is required");
        }


            $build_query = Bag::with(['site:id,name']);
            $next_or_current_site_id = (int)($filters['next_or_current_site_id']);
            if ($next_or_current_site_id !== 0) // is this even correct what is zero ?
            {
                $build_query->where('next_or_current_site_id', $next_or_current_site_id);
            }

            if (array_key_exists('type', $filters) && $filters['type'] != null) {

                $type = (int)$filters['type'];
                if ($type !== -1) {
                    $build_query->where('type', $type);
                }

            }

            if (array_key_exists('status', $filters) && $filters['status'] != null) {

                $status = (int)$filters['status'];
                if ($status !== -1) {
                    $build_query->where('status', $status);
                }

            }

            return $build_query->get();

    }

public function getBagsQuery($filters)
    {
        $build_query = Bag::with(['site:id,name']);
        $next_or_current_site_id = (int)($filters['next_or_current_site_id']);
        if ($next_or_current_site_id !== 0) { // is this even correct what is zero ?
            $build_query->where('next_or_current_site_id', $next_or_current_site_id);
        }

        $type = (int)$filters['type'];
        if ($type !== -1) {
            $build_query->where('type', $type);
        }


        $status = (int)$filters['status'];
        if ($status !== -1) {
            $build_query->where('status', $status);
        }



        return $build_query->orderBy('type')->orderBy('number');
    }


    public function getIncomingTransfersQuery($filters)
    {
        try {
            $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns

            $build_query =
             Transfer::withCount('transfer_bags')
                ->with(['created_by_user:id,name', 'departure_site:id,name', 'acknowledged_by_user:id,name'])
                ->where('destination_site_id', $filters['user']->site_id)
                ->where('status', TransferStatus::IN_TRANSIT);

            $departure_site_id = ($filters['departure_site_id']);
            if ($departure_site_id !== -1) // is this even correct what is zero ?
            {
                $build_query->where('departure_site_id', $departure_site_id);
            }

            return $build_query->orderByRaw($query);

        } catch (Exception $ex) {
            //Do something here
        }
    }


    public function getDispatchedTransfersQuery($filters)
    {
        try {
            $query = "created_at desc";
            $build_query = Transfer::withCount('transfer_bags')->with(['created_by_user:id,name', 'departure_site:id,name', 'destination_site:id,name', 'acknowledged_by_user:id,name'])->where('departure_site_id', $filters['user']->site_id);
            $destination_site_id = ($filters['destination_site_id']);
            if ($destination_site_id !== -1) // is this even correct what is zero ?
            {
                $build_query->where('destination_site_id', $destination_site_id);
            }

            if ($filters['created_by'] !== -1) // is this even correct what is zero ?
            {
                $build_query->where('created_by', $filters['created_by']);
            }


            $user_time_zone = 'Africa/Lagos'; // temporal
            if ($filters['start_date'] != null) {
                $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) >= '{$filters['start_date']}'");
            }

            if ($filters['end_date'] != null) {
                $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) <= '{$filters['end_date']}'");
            }

            return $build_query->orderByRaw($query);
        } catch (Exception $ex) {
            //Do something here
        }
    }



    public function getOnSiteBagsQuery($filters)
    {
        try {
            $query = "created_at desc";
            $build_query = Transfer::withCount('transfer_bags')->with(['created_by_user:id,name', 'departure_site:id,name', 'destination_site:id,name', 'acknowledged_by_user:id,name'])->where('departure_site_id', $filters['user']->site_id);
            $destination_site_id = ($filters['destination_site_id']);
            if ($destination_site_id !== -1) // is this even correct what is zero ?
            {
                $build_query->where('destination_site_id', $destination_site_id);
            }

            if ($filters['created_by'] !== -1) // is this even correct what is zero ?
            {
                $build_query->where('created_by', $filters['created_by']);
            }


            $user_time_zone = 'Africa/Lagos'; // temporal
            if ($filters['start_date'] != null) {
                $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) >= '{$filters['start_date']}'");
            }

            if ($filters['end_date'] != null) {
                $build_query->whereRaw("CAST(CONVERT_TZ(created_at , 'UTC' , '$user_time_zone') AS DATE) <= '{$filters['end_date']}'");
            }

            return $build_query->orderByRaw($query);
        } catch (Exception $ex) {
            //Do something here
        }
    }

    public function getIncomingTransfers($filters)
    {
        try {
            $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns

            $build_query =
             Transfer::withCount('transfer_bags')
                ->with(['created_by_user:id,name', 'departure_site:id,name'])
                ->where('destination_site_id', $filters['user']->site_id)
                ->where('status', TransferStatus::IN_TRANSIT);

            // $departure_site_id = ($filters['departure_site']);

            // if ($departure_site_id !== 0) // is this even correct what is zero ?
            // {
            //     $build_query->where('departure_site_id', $departure_site_id);
            // }

            return $build_query;
        } catch (Exception $ex) {

        }
    }


    public function  getOutgoingTransfers($filters)
    {
        try {
            $query = "created_at desc"; // "status ASC, id ASC"; // Ordering columns

            $build_query =
             Transfer::withCount('transfer_bags')
                ->with(['created_by_user:id,name', 'destination_site:id,name'])
                ->where('departure_site_id', $filters['user']->site_id);

            return $build_query->orderByRaw($query);
        } catch (Exception $ex) {

        }
    }


    public function getTransfer($transfer_id)
    {
        //:id,weight,arrival_weight,departure_weight,status,created_by_user,acknowledged_by_user
        $transfer = Transfer::with(['transfer_bags', 'departure_site:id,name', 'destination_site:id,name', 'created_by_user:id,name'])->where('id', (int)$transfer_id)->first();
        return $transfer;
    }


}
