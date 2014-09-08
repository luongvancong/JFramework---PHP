@extends('backend/layouts/iframe')
<?php
$form = new FormMaker(array('errors' => $errors));
?>
{{-- Page title --}}
@section('title')
Add @page_title@ ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
   <h3>
      Add @page_title@
      <div class="pull-right">
         <a href="javascript:window.history.go(-1)" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-circle-arrow-left"></i> Trở lại</a>
      </div>
   </h3>
   <p style="border-bottom:1px solid #ccc;padding-bottom:5px">Những ô có dấu <span class="text-danger">*</span> là bắt buộc phải nhập</p>
</div>

{{ $form->openForm() }}
   <!-- CSRF Token -->
   {{ $form->hidden(array('name' => '_token', 'value' => csrf_token())) }}

   <!-- Tabs Content -->
   <div class="tab-content">
   <?php
      @form_controls@
   ?>
   </div>
   <p class="clearfix"></p>
   <!-- Form Actions -->
   <div class="form-group">
      <div class="col-sm-6 col-sm-offset-2">
         <a class="btn btn-link" href="javascript:window.history.go(-1)">Hủy</a>

         <button type="reset" class="btn">Xóa dữ liệu</button>

         <button type="submit" class="btn btn-success">Cập nhật</button>
      </div>
   </div>
{{ $form->closeForm() }}
@stop
