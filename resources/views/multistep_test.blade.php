@extends('layouts.main_layout')

@section('page_dependencies')
    <link rel="stylesheet" href="/azmid_components/multistep_form/assets/css/style.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">

            <div class="box box-primary">
                <form role="form" action="" method="post" class="multistep-form form-horizontal">

                    <fieldset>
                        <div class="box-header with-border">
                            <i class="fa fa-user pull-right"></i>
                            <h3 class="box-title">Step 1 / 3</h3>
                            <p>Tell us who you are:</p>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="first_name" class="col-sm-3 control-label">First Name</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="surname" class="col-sm-3 control-label">Surname</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="about-yourself" class="col-sm-3 control-label">About Yourself</label>

                                <div class="col-sm-9">
                                    <textarea name="about-yourself" placeholder="About yourself..." class="form-control" id="about-yourself"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="test-email" class="col-sm-3 control-label">Send Test Email</label>

                                <div class="col-sm-9">
                                    <a href="/testemail" type="button" class="btn btn-primary">Send Email</a>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-primary btn-next pull-right">Next</button>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="box-header with-border">
                            <i class="fa fa-key pull-right"></i>
                            <h3 class="box-title">Step 2 / 3</h3>
                            <p>Set up your account:</p>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Email</label>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-3 control-label">Password</label>

                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cofirm-password" class="col-sm-3 control-label">Confirm Password</label>

                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="cofirm-password" name="cofirm-password" placeholder="Repeat Password">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn  btn-primary btn-previous">Previous</button>
                            <button type="button" class="btn btn-primary btn-next pull-right">Next</button>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="box-header with-border">
                            <i class="fa fa-twitter pull-right"></i>
                            <h3 class="box-title">Step 3 / 3</h3>
                            <p>Social media profiles:</p>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="facebook" class="col-sm-3 control-label">Facebook</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="facebook" name="facebook" placeholder="Facebook..." required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="twitter" class="col-sm-3 control-label">Twitter</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter..." required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="google_plus" class="col-sm-3 control-label">Google Plus</label>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="google_plus" name="google_plus" placeholder="Google Plus..." required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-primary btn-previous">Previous</button>
                            <button type="submit" class="btn btn-success pull-right">Sign me up!</button>
                        </div>
                    </fieldset>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
    <script src="/azmid_components/multistep_form/assets/js/jquery.backstretch.min.js"></script>
    <script src="/azmid_components/multistep_form/assets/js/retina-1.1.0.min.js"></script>
    <script src="/azmid_components/multistep_form/assets/js/scripts.js"></script>
@endsection