<?php
namespace App\Services;

use App\Site;
use App\User;
use App\State;
use App\SiteType;




class SiteServices
{
    function __construct()
    {
        // echo "Site service has been created\n";

    }
    /*
    expected data:
	  [
	  'id'=>'78748',
	  'name' => 'Test Site',
	  'created_at' => now(),
	  'updated_at'=> now()
	  ],
     */
    function CreateSite($create_site_request){
        \App\Site::create([
		'id' => 234104,
		'name' => 'LOSzz',
		'created_at' => now(),
		'updated_at'=> now()
		]);
    }
    function getSitesForManifestView($data)
    {
        if(!$data['user']->hasAnyRole('Administrator', 'operations'))
        {
            return Site::pluck('name','id');

        }
        else
        {
            return Site::pluck('name','id');
        }

    }

    function getAllSites()
    {
        return Site::pluck('name','id');
    }

    function getAllSitesv2()
    {
        return Site::where('can_dispatch_or_acknowledge_manifest' , '!=' , 0)->pluck('name','id');
    }

    public function getFranchisees()
    {
        return Site::where('is_a_franchise' , '=' , 1)->pluck('name','id');
    }

    function getSites()
    {
        return Site::all();
    }

    function getSitesQuery()
    {
        return Site::with(['parent_site']);
    }

    function getSite($site_id)
    {
        $site = Site::with(['users' => function($query) {
            $query->where('role_id', '=', 3); //Site Administrator
        }])->where('id', $site_id)->get();

        // dd($site);
        return $site;
    }

    function getSiteUsers($site_id)
    {
        $users = User::where('site_id', $site_id)->get(); // get only some columns
        return $users; //could be none
    }

    public function getStates()
    {
        return State::all();
    }

    public function getSiteTypes()
    {
        return SiteType::all();
    }
}
