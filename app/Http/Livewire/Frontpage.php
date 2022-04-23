<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Frontpage extends Component
{
    public $urlslug;
    public $title,$content;

    public function mount($urlslug=null)
    {
        $this->retrieveContent($urlslug);
    }

    public function retrieveContent($urlslug)
    {
        //get Home Page if empty
        if(empty($urlslug)){
            $data=Page::where('is_default_home',true)->first();
        }else{
            $data=Page::where('slug',$urlslug)->first();
            if(!$data){
                $data=Page::where('is_default_not_found',true)->first();
            }
        }
        $this->title=$data->title;
        $this->content=$data->content;
    }
    
    /**
     * Get all NAVBAR LINK 
     *
     * @return void
     */
    private function sideBarLinks()
    {
        return DB::table('navigation_menus')
        ->where('type','=','SideNav')
        ->orderBy('sequence','asc')
        ->orderBy('created_at','asc')
        ->get();
    }
    private function topBarLink()
    {
        return DB::table('navigation_menus')
        ->where('type','=','TopNav')
        ->orderBy('sequence','asc')
        ->orderBy('created_at','asc')
        ->get();
    }


    public function render()
    {
        return view('livewire.frontpage',[
             'sidebarlinks'=>$this->sideBarLinks(),
             'topbarlink'=>$this->topBarLink(),

        ])->layout('layouts.frontpage');
    }
}
