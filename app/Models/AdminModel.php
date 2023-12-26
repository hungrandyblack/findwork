<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class AdminModel extends Model
{
    use HasFactory;
    use UploadFileTrait;
    const ACTIVE    = 1;
    const INACTIVE  = 0;
    const DRAFT     = -1;

    static $upload_dir = 'uploads';

    public static function setUploadDir( $upload_dir ){
        self::$upload_dir = $upload_dir;
    }
    
    public static function getItems($request = null,$limit = 20,$table = ''){
        $model = new self;
        $tableName = $model->getTable();
        if($table){
            $modelClass = '\App\Models\\' . $table;
            $query = $modelClass::query(true);
        }else{
            $query = self::query(true);
        }
        if($request->type && Schema::hasColumn($tableName, 'type')){
            $query->where('type',$request->type);
        }
        if($request->name){
            $query->where('name','LIKE','%'.$request->name.'%');
        }
        if($request->status){
            $query->where('status',$request->status);
        }
        $items = $query->paginate($limit);
        return $items;
    }
    public static function findItem($id){
        return self::findOrFail($id);
    }
    public static function saveItem($request){
        $data = $request->all();
        if(!$request->slug && $request->name){
            $data['slug'] = Str::slug($request->name);
        }
        if ($request->hasFile('image')) {
            $data['image'] = self::uploadFile($request->file('image'), self::$upload_dir);
        } 
        self::create($data);
    }
    public static function updateItem($id,$request){
        $item = self::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            self::deleteFile($item->image);
            $data['image'] = self::uploadFile($request->file('image'), self::$upload_dir);
        } 
        $item->update($data);
    }
    public static function deleteItem($id){
        $item = self::findItem($id);
        self::deleteFile($item->image);
        return $item->delete();
    }

    // Attributes
    public function getStatusFmAttribute(){
        switch ($this->status) {
            case self::DRAFT:
                return '<span class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">'.__('sys.draf').'</span>';
                break;
            case self::ACTIVE:
                return '<span class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">'.__('sys.active').'</span>';
                break;
            case self::INACTIVE:
                return '<span class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">'.__('sys.inactive').'</span>';
                break;
        }
    }
    public function getCreatedAtFmAttribute(){
        return date('d-m-Y',strtotime($this->created_at));
    }
    public function getImageFmAttribute(){
        if( !$this->image ){
            return asset('admin-assets/images/default-image.png');
        }
        return asset($this->image);
    }
}
