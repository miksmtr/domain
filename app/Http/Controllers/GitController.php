<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Domain;
use App\Models\GitDomain;
use Illuminate\Http\Request;


class GitController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_new_git_domain(Request $request)
    {
        $request->validate([
            'new_domain_name' => 'required',
        ]);
        $response = null;
        $newDomainName = $request->get('new_domain_name');
        $domain = Domain::where('name', $newDomainName)->get()->first();

        $domain_id = $domain->id;
        $codes = Code::all();
        $git_id = 0;
        $git_domains = [];



        foreach ($codes as  $value) {
            $git_domains = GitDomain::where("git_id", $value->id)->get();
            $git_domains_limit = count($git_domains);
            if ($value->limit > ($git_domains_limit )) {
                $git_id = $value->git_id;
                break;
            }
        }


/*         if (count($git_domains_limit_array) > 0) {
            $minimum_value = min($git_domains_limit_array);
            $git_id = array_search($minimum_value, $git_domains_limit_array);
        } */



        $git_domain = GitDomain::where('domain_id', $domain_id)->get()->first();
        if (!$git_domain) {
            $new_git_domain = new GitDomain();
            $new_git_domain->git_id = $git_id;
            $new_git_domain->domain_id = $domain_id;
            $new_git_domain->setup = 0;
            $new_git_domain->save();
            $response = $new_git_domain->id;
        } else {
            $response = $git_domain->id;
        }


        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete_git_domain(Request $request)
    {
        $request->validate([
            'old_domain_name' => 'required',
        ]);
        $oldDomainName = $request->get('old_domain_name');
        $domain_id = Domain::where('name', $oldDomainName)->get()->first()->id;

        $git_domain = GitDomain::where('domain_id', $domain_id)->get()->first();
        try {
            $git_domain->delete();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
