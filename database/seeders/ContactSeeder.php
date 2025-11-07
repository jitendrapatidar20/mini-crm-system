<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\ContactEmail;
use App\Models\ContactPhone;
use App\Models\CustomValue;

class ContactSeeder extends Seeder {
  public function run(){
    $c1 = Contact::create(['name'=>'Jitendra Patidar','slug'=>'jitendra-patidar','email'=>'jitendrapatidar@gmail.com','phone'=>'9929167751','gender'=>'male']);
    ContactEmail::create(['contact_id'=>$c1->id,'email'=>'j.patidar@gmail.com']);
    ContactPhone::create(['contact_id'=>$c1->id,'phone'=>'9929167752']);
    CustomValue::create(['contact_id'=>$c1->id,'custom_field_id'=>1,'value'=>'1989-04-21']);

    $c2 = Contact::create(['name'=>'Manisha Patidar','slug'=>'manisha-patidar','email'=>'Manisha@gmail.com','phone'=>'9000000100','gender'=>'female']);
    ContactEmail::create(['contact_id'=>$c2->id,'email'=>'m.patidar@gmail.com']);
    CustomValue::create(['contact_id'=>$c2->id,'custom_field_id'=>2,'value'=>'Acme Corp']);
  }
}
