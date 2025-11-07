<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CustomField;

class CustomFieldSeeder extends Seeder 
{
  public function run(){
    CustomField::create(['name'=>'Birthday','slug'=>'birthday','type'=>'date']);
    CustomField::create(['name'=>'Company','slug'=>'company','type'=>'text']);
    CustomField::create(['name'=>'Address','slug'=>'address','type'=>'text']);
  }
  
}
