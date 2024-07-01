<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_number',
        'email',
        'address',
        'photo',
        'logo',
        'admin_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_path',
        'logo_path',
    ];

    public function admin() {
        return $this->belongsTo(Admin::class)->withDefault(['name' => 'Admin User']);
    }

    public function getPhotoPathAttribute() {
        return ($this->photo) ? file_url($this->photo, 'application') : "";
    }

    public function getLogoPathAttribute() {
        return ($this->logo) ? file_url($this->logo, 'application') : "";
    }

}
