<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuari extends Model
{
    public $table="usuari";
    public $timestamps=false;
    public $fillable=array('email', 'password', 'nom', 'cognom', 'admin', 'validat', 'pswrdreset');
    use HasFactory;
}
