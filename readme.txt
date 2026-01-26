1. 2 jaygay tei jate payment kora jay system ta korte hobe https://prnt.sc/0iXQMZmmZj3F



//issue
1. After completing a search in the POS section I can't add any product for checkout
2.


Displaying validation messages when a form is submitted (without AJAX) :

<input type="text" name="name" class="form-control {{ customValid('name', $errors) }}">
@if ($errors->has('package_expire_day'))
  <p class="mb-0 text-danger">{{ $errors->first('name') }}</p>
@endif



Displaying validation messages when a form is submitted (with AJAX) :

<input type="text" name="name" class="form-control err_name">
<p id="err_name" class="text-danger em"></p>


Displaying validation messages when a form is submitted (with text-input component) :

<x-text-input label="label_name_here" col="12" value="your_value" name="stripe_secret" placeholder="Enter stripe secret" />
