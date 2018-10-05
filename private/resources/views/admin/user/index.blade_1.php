@extends('backend.layouts.app')
@section('content')
<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="row">
                            <div class="col-md-12 ">
<div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Line Inputs</span>
                                        </div>                                       
                                    </div>
                                    <div class="portlet-body form">
                                        <form role="form">
                                            <div class="form-body">
                                                <div class="form-group form-md-line-input">
                                                    <input type="text" class="form-control" id="form_control_1" placeholder="Enter your name">
                                                    <label for="form_control_1">Regular input</label>
                                                </div>
                                            </div>
                                            <div class="form-actions noborder">
                                                <button type="button" class="btn blue">Submit</button>
                                                <button type="button" class="btn default">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- END SAMPLE FORM PORTLET-->
                                </div>
                                </div>

@endsection
