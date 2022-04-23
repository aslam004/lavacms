<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Pages extends Component
{
    use WithPagination;

    public $modalVisible=false;
    public $modalConfirmDelete=false;
    public $slug,$title,$content,$modelId;
    public $isSetToDefaultHomePage,$isSetToDefaultNotFoundPage;


    public function rules()
    {
        return[
            'title'=>'required',
            'slug'=>['required',Rule::unique('pages','slug')->ignore($this->modelId)],
            'content'=>'required',
        ];
    }
        
    /**
     * Mount Function
     *
     * @return void
     */
    public function mount()
    {
        //resetPagination
        $this->resetPage();
    }

    /**
     * Create func
     *
     * @return void
     */
    public function create()
    {
        $this->validate();
        $this->unassignDefaultHomePage();
        $this->unassignDefaultNotFound();
        Page::create($this->modelData());
        $this->modalVisible=false;
        $this->reset();

        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'New Page',
            'eventMessage' => 'Another page has been created!',
        ]);
    }

    /**
     * Read func
     *
     * @return void
     */
    public function read()
    {
        return Page::paginate(5);
    }

    public function update()
    {
        $this->validate();
        $this->unassignDefaultHomePage();
        $this->unassignDefaultNotFound();
        Page::find($this->modelId)->update($this->modelData());
        $this->modalVisible=false;

        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'Updated Page',
            'eventMessage' => 'There is a page (' . $this->modelId . ') that has been updated!',
        ]);
    }

    public function delete()
    {
        Page::destroy($this->modelId);
        $this->modalConfirmDelete=false;
        $this->resetPage();

        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'Deleted Page',
            'eventMessage' => 'The page (' . $this->modelId . ') has been deleted!',
        ]);
    }

    public function updatedTitle($value)
    {
        $this->slug=Str::slug($value);
    }

    public function updatedIsSetToDefaultHomePage()
    {
        $this->isSetToDefaultNotFoundPage=null;
    }
    public function updatedIsSetToDefaultNotFoundPage()
    {
        $this->isSetToDefaultHomePage=null;
    }

    /**
     * Show Modal form
     * create Func
     *
     * @return void
     */
    public function createShowModal()
    {
        $this->resetValidation();
        $this->reset();
        $this->modalVisible=true;
        
    }

    /**
     * show form modal
     *
     * @param  mixed $id
     * @return void
     */
    public function updateShowModal($id)
    {
        $this->resetValidation();
        $this->reset();
        $this->modelId=$id;
        $this->modalVisible=true;
        $this->loadModel();
    }

    /**
     * Show Delete Confirmation Modal
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteShowModal($id)
    {
        $this->modelId=$id;
        $this->modalConfirmDelete=true;
        
    }
    /**
     * Load Model component
     *
     * @return void
     */
    public function loadModel()
    {
        $data=Page::find($this->modelId);
        $this->title=$data->title;
        $this->slug=$data->slug;
        $this->content=$data->content;
        $this->isSetToDefaultHomePage= !$data->is_default_home?null:true;
        $this->isSetToDefaultNotFoundPage= !$data->is_default_not_found?null:true;
    }

    /**
     * Data mapped here
     *
     * @return void
     */
    public function modelData()
    {
        return[
            'title'=>$this->title,
            'slug'=>$this->slug,
            'content'=>$this->content,
            'is_default_home'=>$this->isSetToDefaultHomePage,
            'is_default_not_found'=>$this->isSetToDefaultNotFoundPage,
        ];
    }

    /**
     * fungsi untuk mengatur hanya 1 yang dimasukan ke homepage
     * mengatur database
     *
     * @return void
     */
    public function unassignDefaultHomePage()
    {
        if ($this->isSetToDefaultHomePage != null){
            Page::where('is_default_home',true)->update([
                'is_default_home'=>false,

            ]);
        }
    }
    public function unassignDefaultNotFound()
    {
        if ($this->isSetToDefaultNotFoundPage != null){
            Page::where('is_default_not_found',true)->update([
                'is_default_not_found'=>false,

            ]);
        }
    }

    public function dispatchEvent()
    {
        $this->dispatchBrowserEvent('event-notification', [
            'eventName' => 'Sample Event',
            'eventMessage' => 'You have a sample event notification!',
        ]);
    }

    public function render()
    {
        return view('livewire.pages',[
            'data'=>$this->read()
        ]);
    }
}
