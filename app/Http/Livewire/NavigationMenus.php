<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\NavigationMenu;

class NavigationMenus extends Component
{
    use WithPagination;

    public $modalFormVisible;
    public $modalConfirmDelete;

    public $modelId,$label,$slug;
    public $sequence=1;
    public $type='SideNav';
    
    public function rules()
    {
        return [
            'label' => 'required',
            'slug' => 'required',
            'sequence' => 'required',
            'type' => 'required',
        ];
    } 
    public function loadModel()
    {
        $data=NavigationMenu::find($this->modelId);
        $this->label=$data->label;
        $this->slug=$data->slug;
        $this->type=$data->type;
        $this->sequence=$data->sequence;
    }
    public function modelData()
    {
        return [
            'label'=>$this->label,
            'slug'=>$this->slug,
            'type'=>$this->type,
            'sequence'=>$this->sequence,
        ];
    }

// CRUD//
    public function create()
    {
        $this->validate();
        NavigationMenu::create($this->modelData());
        $this->modalFormVisible = false;
        $this->reset();
    }
    public function read()
    {
        return NavigationMenu::Paginate(5);
    }
    public function update()
    {
        $this->validate();
        NavigationMenu::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible=false;
    }
    public function delete()
    {
        NavigationMenu::destroy($this->modelId);
        $this->modalConfirmDelete=false;
        $this->resetPage();
    }

// MODAL//
    /**
     * show modal create
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible=true;
    }
    public function updateShowModal($id)
    {
        $this->resetValidation();
        $this->reset();
        $this->modalFormVisible=true;
        $this->modelId=$id;
        $this->loadModel();
    }
    public function deleteShowModal($id)
    {
        $this->modelId = $id;
        $this->modalConfirmDelete = true;
    }  
    public function render()
    {
        return view('livewire.navigation-menus',[
            'data'=>$this->read()
        ]);
    }
}
