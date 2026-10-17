<?php
namespace App\Http\Requests;

// Same rules as store (cover stays optional on update).
class UpdateBookRequest extends StoreBookRequest {}
