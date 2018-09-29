<div class="container-fluid  full-height" id="register_form-wrap">
    <div id="register_form-main" class="text-center row">
        <div class="col-xs-10 col-xs-offset-1 text-center">
            <img class="logo" src="<?php echo plugin_dir_url(__FILE__); ?>../media/images/outline-logo-b.png" />
            <div class="outline-text"><?php _e( "Baptism Pre-register", 'laplacita' ); ?></div>
        </div>
        <div class="col-xs-12">
            <!-- multistep form -->
            <form autocomplete="off" novalidate name="register-form" id="register-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="form multistep-form col-xs-12">
                <input type="hidden" name="register-form" value="1" />
                <input type="hidden" name="thankyou-page" value="<?php echo $redirect ? '1' : '0'; ?>" />
                <input type="hidden" name="action" value="baptism_register_form">
                <input type="hidden" name="child-id" value="<?php echo $child['id'] ? $child['id'] : "0" ?>" />
                <!-- progressbar -->
                <ul class="progressbar">
                    <li class="active"><?php _e( "Child", 'laplacita' ); ?></li>
                    <li><?php _e( "Parents", 'laplacita' ); ?></li>
                    <li><?php _e( "Godparents", 'laplacita' ); ?></li>
                    <li><?php _e( "Finish", 'laplacita' ); ?></li>
                </ul>
                <!-- fieldsets -->
                <fieldset>

                    <h2 class="fs-title"><?php _e( "Child", 'laplacita' ); ?></h2>
                    <h3 class="fs-subtitle"><?php _e( "Enter the child's info", 'laplacita' ); ?></h3>

                    <div class="row">
                      <div class="form-group col-sm-6">
                        <input required type="text" class="form-control" value="<?php echo $child['first_name'] ? $child['first_name'] : "" ?>" name="first_name"
                        placeholder="<?php _e( "First Name", 'laplacita'); ?>" />
                      </div>
                      <div class="form-group col-sm-6">
                        <input type="text" class="form-control" value="<?php echo $child['middle_name'] ? $child['middle_name'] : "" ?>" name="middle_name"
                        placeholder="<?php _e( "Middle Name", 'laplacita'); ?>" />
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-sm-6">
                        <input required type="text" class="form-control" value="<?php echo $child['last_name'] ? $child['last_name'] : "" ?>" name="last_name"
                        placeholder="<?php _e( "Last Name", 'laplacita'); ?>" />
                      </div>
                      <div class="form-group col-sm-6">
                        <input required type="text" value="<?php echo $child['birthdate'] ? date("m/d/Y", strtotime($child['birthdate'])) : "" ?>" class="form-control date birthdate" name="birthdate"
                        placeholder="<?php _e( "Birthdate", 'laplacita'); ?>" />
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-sm-6">
                        <input required type="text" value="<?php echo $child['birthplace'] ? $child['birthplace'] : "" ?>" class="form-control" name="birthplace"
                        placeholder="<?php _e( "Birthplace", 'laplacita'); ?>" />
                      </div>
                      <div class="form-group col-sm-6">
                        <div data-quantity="2" class="inline-wrap">
                            <label class="radio radio-inline">
                                <input required type="radio" <?php echo $child['gender'] == "male" ? "checked" : "" ?> name="gender" id="radio-male" value="male">
                                <span class="outer"><span class="inner"></span></span><?php _e( "Boy", 'laplacita' ); ?>
                            </label>
                            <label class="radio radio-inline">
                                <input required type="radio" <?php echo $child['gender'] == "female" ? "checked" : "" ?> name="gender" id="radio-female" value="female">
                                <span class="outer"><span class="inner"></span></span><?php _e( "Girl", 'laplacita' ); ?>
                            </label>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-sm-6 col-sm-offset-3">
                        <input type="button" name="next" class="next action-button"
                        value="<?php _e( "Next", 'laplacita'); ?>" />
                      </div>
                    </div>

                </fieldset>

                <fieldset>


                    <section>
                      <h2 class="fs-title"><?php _e( "Parents", 'laplacita' ); ?></h2>
                      <h3 class="fs-subtitle"><?php _e( "Enter the parents' info", 'laplacita' ); ?></h3>

                      <div class="row">

                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['main_phone'] ? $child['main_phone'] : "" ?>" class="form-control" name="main_phone"
                          placeholder="<?php _e( "Main Phone", 'laplacita' ); ?>" />
                        </div>

                        <div class="form-group col-sm-6">
                          <input required type="email" value="<?php echo $child['contact_email'] ? $child['contact_email'] : "" ?>" class="form-control" name="contact_email"
                          placeholder="<?php _e( "Contact Email", 'laplacita' ); ?>" />
                        </div>

                      </div>

                      <div class="row">

                        <div class="form-group col-sm-6">
                            <input required type="text" value="<?php echo $child['address'] ? $child['address'] : "" ?>" class="form-control" name="address"
                            placeholder="<?php _e( "Street Address", 'laplacita' ); ?>" />
                        </div>

                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['city'] ? $child['city'] : "" ?>" class="form-control" name="city"
                          placeholder="<?php _e( "City", 'laplacita' ); ?>" />
                        </div>

                      </div>

                      <div class="row">
                        
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['state'] ? $child['state'] : "" ?>" class="form-control" name="state"
                          placeholder="<?php _e( "State", 'laplacita' ); ?>" />
                        </div>
                        
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['zip'] ? $child['zip'] : "" ?>" class="form-control" name="zip"
                          placeholder="<?php _e( "Zip Code", 'laplacita' ); ?>" />
                        </div>
                      </div>
                    </section>


                    <section>
                      <div class="row">
                        <h2 class="fs-title"><?php _e( "Father", 'laplacita' ); ?></h2>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['father_name'] ? $child['father_name'] : "" ?>" class="form-control" name="father_name"
                          placeholder="<?php _e( "Father's First Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="text" value="<?php echo $child['father_middle'] ? $child['father_middle'] : "" ?>" class="form-control" name="father_middle"
                          placeholder="<?php _e( "Father's Middle Name", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['father_last'] ? $child['father_last'] : "" ?>" class="form-control" name="father_last"
                          placeholder="<?php _e( "Father's Last Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="email" value="<?php echo $child['father_email'] ? $child['father_email'] : "" ?>" class="form-control" name="father_email"
                          placeholder="<?php _e( "Father's Email", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="tel" value="<?php echo $child['father_phone'] ? $child['father_phone'] : ""; ?>" class="form-control" name="father_phone"
                          placeholder="<?php _e( "Father's Phone", 'laplacita' ); ?>" />
                        </div>
                      </div>
                    </section>


                    <section>
                      <div class="row">
                        <h2 class="fs-title"><?php _e( "Mother", 'laplacita' ); ?></h2>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['mother_name'] ? $child['mother_name'] : "" ?>" class="form-control" name="mother_name"
                          placeholder="<?php _e( "Mother's First Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="text" value="<?php echo $child['mother_middle'] ? $child['mother_middle'] : "" ?>" class="form-control" name="mother_middle"
                          placeholder="<?php _e( "Mother's Middle Name", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['mother_last'] ? $child['mother_last'] : "" ?>" class="form-control" name="mother_last"
                          placeholder="<?php _e( "Mother's Last Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="email" value="<?php echo $child['mother_email'] ? $child['mother_email'] : "" ?>" class="form-control" name="mother_email"
                          placeholder="<?php _e( "Mother's Email", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="tel" value="<?php echo $child['mother_phone'] ? $child['mother_phone'] : "" ?>" class="form-control" name="mother_phone"
                          placeholder="<?php _e( "Mother's Phone", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['mother_married_name'] ? $child['mother_married_name'] : "" ?>" class="form-control" name="mother_married_name"
                          placeholder="<?php _e( "Mother's Married Last Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <label class="checkbox-inline">
                              <input type="checkbox" <?php echo $child['mmn_birth_certificate'] == 1 ? "checked" : "" ?> name="mmn_birth_certificate" id="mother-birth-certificate" value="1"
                              > <?php _e( "Birth Certificate", 'laplacita' ); ?>
                          </label>
                        </div>
                      </div>
                    </section>


                    <div class="row">
                      <div class="form-group col-xs-6">
                        <input type="button" name="previous" class="previous action-button"
                        value="<?php _e( "Previous", 'laplacita' ); ?>" />
                      </div>
                      <div class="form-group col-xs-6">
                        <input type="button" name="next" class="next action-button"
                        value="<?php _e( "Next", 'laplacita' ); ?>" />
                      </div>
                    </div>

                </fieldset>

                <fieldset>

                    <h2 class="fs-title"><?php _e( "Godparents", 'laplacita' ); ?></h2>
                    <h3 class="fs-subtitle"><?php _e( "Enter the godparents' info", 'laplacita' ); ?></h3>


                    <section>
                      <div class="row">
                        <h2 class="fs-title"><?php _e( "Godfather", 'laplacita' ); ?></h2>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['godfather_name'] ? $child['godfather_name'] : "" ?>" class="form-control" name="godfather_name"
                          placeholder="<?php _e( "Godather's First Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="text" value="<?php echo $child['godfather_middle'] ? $child['godfather_middle'] : "" ?>" class="form-control" name="godfather_middle"
                          placeholder="<?php _e( "Godather's Middle Name", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['godfather_last'] ? $child['godfather_last'] : "" ?>" class="form-control" name="godfather_last"
                          placeholder="<?php _e( "Godather's Last Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="email" value="<?php echo $child['godfather_email'] ? $child['godfather_email'] : "" ?>" class="form-control" name="godfather_email"
                          placeholder="<?php _e( "Godfather's Email", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input type="tel" value="<?php echo $child['godfather_phone'] ? $child['godfather_phone'] : "" ?>" class="form-control" name="godfather_phone"
                          placeholder="<?php _e( "Godfather's Phone", 'laplacita' ); ?>" />
                        </div>
                      </div>
                    </section>

                    <section>
                      <div class="row">
                        <h2 class="fs-title"><?php _e( "Godmother", 'laplacita' ); ?></h2>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['godmother_name'] ? $child['godmother_name'] : "" ?>" class="form-control" name="godmother_name"
                          placeholder="<?php _e( "Godmother's First Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="text" value="<?php echo $child['godmother_middle'] ? $child['godmother_middle'] : "" ?>" class="form-control" name="godmother_middle"
                          placeholder="<?php _e( "Godmother's Middle Name", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['godmother_last'] ? $child['godmother_last'] : "" ?>" class="form-control" name="godmother_last"
                          placeholder="<?php _e( "Godmother's Last Name", 'laplacita' ); ?>" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="email" value="<?php echo $child['godmother_email'] ? $child['godmother_email'] : "" ?>" class="form-control" name="godmother_email"
                          placeholder="<?php _e( "Godmother's Email", 'laplacita' ); ?>" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input type="tel" value="<?php echo $child['godmother_phone'] ? $child['godmother_phone'] : "" ?>" class="form-control" name="godmother_phone"
                          placeholder="<?php _e( "Godmother's Phone", 'laplacita' ); ?>" />
                        </div>
                      </div>
                    </section>


                    <div class="row">
                      <div class="form-group col-xs-6">
                        <input type="button" name="previous" class="previous action-button"
                        value="<?php _e( "Previous", 'laplacita' ); ?>" />
                      </div>
                      <div class="form-group col-xs-6">
                        <input type="button" name="next" class="next action-button"
                        value="<?php _e( "Next", 'laplacita' ); ?>" />
                      </div>
                    </div>

                </fieldset>

                <fieldset>

                    <h2 class="fs-title"><?php _e( "Finish", 'laplacita' ); ?></h2>
                    <h3 class="fs-subtitle"><?php _e( "Finish up and submit", 'laplacita' ); ?></h3>

                    <div class="row">
                      <div class="form-group col-xs-12">
                        <textarea class="form-control" name="note"
                        placeholder="<?php _e("Notes", 'laplacita' ); ?>"
                        ><?php echo $child['note'] ? $child['note'] : "" ?></textarea>
                      </div>
                    </div>


                    <div class="row">
                      <div class="form-group col-xs-6">
                        <input type="button" name="previous" class="previous action-button"
                        value="<?php _e( "Previous", 'laplacita' ); ?>" />
                      </div>
                      <div class="form-group col-xs-6">
                        <input type="submit" name="register-submit" class="submit action-button"
                        value="<?php _e( "Submit", 'laplacita' ); ?>" />
                      </div>
                    </div>

                </fieldset>

                <!-- <div class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3">
                  <input class="action-button" type="submit" name="save-for-later" id="save-for-later" value="Save for later" />
                </div> -->

            </form>
        </div>
    </div>
</div>
<div id="register_form-bg"></div>
