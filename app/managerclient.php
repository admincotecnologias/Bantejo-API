<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class managerclient extends Model {
	// Relationships
	/**
	 * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'managerclient';

	use SoftDeletes;

	protected $fillable = ['idclient','name','lastname','rfc','phone',];

	protected $dates = ['deleted_at',];

	protected $appends = ['filepath', 'filename'];
	public function getFilepathAttribute(){
		if($this->idfile!=null){
			return FileClient::where('id', $this->idfile)->first()->path;
		}else{
			return null;
		}
	}
	public function getFilenameAttribute(){
		if($this->idfile!=null){
			return FileClient::where('id', $this->idfile)->first()->name;
		}else{
			return null;
		}
	}

	


	// Relationships

}
