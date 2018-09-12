<div class="container-fluid  full-height" id="register_form-wrap">
    <div id="register_form-main" class="text-center row">
        <div class="col-xs-10 col-xs-offset-1 text-center">
            <img class="logo" src="<?php echo plugin_dir_url(__FILE__); ?>../media/images/outline-logo-w.png" />
            <div class="outline-text-white">Baptism Pre-register</div>
        </div>
        <div class="col-xs-12">
            <!-- multistep form -->
            <form novalidate name="register-form" id="register-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="form multistep-form col-xs-12">
                <input type="hidden" name="register-form" value="1" />
                <input type="hidden" name="thankyou-page" value="<?php echo $redirect ? '1' : '0'; ?>" />
                <input type="hidden" name="action" value="baptism_register_form">
                <input type="hidden" name="child-id" value="<?php echo $child['id'] ? $child['id'] : "0" ?>" />
                <!-- progressbar -->
                <ul class="progressbar">
                    <li class="active">Child</li>
                    <li>Parents</li>
                    <li>Godparents</li>
                    <li>Finish</li>
                </ul>
                <!-- fieldsets -->
                <fieldset>

                    <h2 class="fs-title">Child</h2>
                    <h3 class="fs-subtitle">Enter the child's info</h3>

                    <div class="row">
                      <div class="form-group col-sm-6">
                        <input required type="text" class="form-control" value="<?php echo $child['first_name'] ? $child['first_name'] : "" ?>" name="first-name" placeholder="First Name" />
                      </div>
                      <div class="form-group col-sm-6">
                        <input type="text" class="form-control" value="<?php echo $child['middle_name'] ? $child['middle_name'] : "" ?>" name="middle-name" placeholder="Middle Name" />
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-sm-6">
                        <input required type="text" class="form-control" value="<?php echo $child['last_name'] ? $child['last_name'] : "" ?>" name="last-name" placeholder="Last Name" />
                      </div>
                      <div class="form-group col-sm-6">
                        <input required type="text" value="<?php echo $child['birthdate'] ? date("m/d/Y", strtotime($child['birthdate'])) : "" ?>" class="form-control date birthdate" name="birthdate" placeholder="Birthdate" />
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-sm-6">
                        <input required type="text" value="<?php echo $child['birthplace'] ? $child['birthplace'] : "" ?>" class="form-control" name="birthplace" placeholder="Birthplace" />
                      </div>
                      <div class="form-group col-sm-6">
                        <div data-quantity="2" class="inline-wrap">
                            <label class="radio radio-inline">
                                <input required type="radio" <?php echo $child['gender'] == "male" ? "checked" : "" ?> name="gender" id="radio-male" value="male">
                                <span class="outer"><span class="inner"></span></span>Boy
                            </label>
                            <label class="radio radio-inline">
                                <input required type="radio" <?php echo $child['gender'] == "female" ? "checked" : "" ?> name="gender" id="radio-female" value="female">
                                <span class="outer"><span class="inner"></span></span>Girl
                            </label>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-sm-6 col-sm-offset-3">
                        <input type="button" name="next" class="next action-button" value="Next" />
                      </div>
                    </div>

                </fieldset>

                <fieldset>


                    <section>
                      <h2 class="fs-title">Parents</h2>
                      <h3 class="fs-subtitle">Enter the parents' info</h3>

                      <div class="row">
                        <div class="form-group col-sm-12">
                          <input required type="email" value="<?php echo $child['contact_email'] ? $child['contact_email'] : "" ?>" class="form-control" name="contact-email" placeholder="Contact Email" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                            <input required type="text" value="<?php echo $child['address'] ? $child['address'] : "" ?>" class="form-control" name="address" placeholder="Street Address" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['city'] ? $child['city'] : "" ?>" class="form-control" name="city" placeholder="City" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['state'] ? $child['state'] : "" ?>" class="form-control" name="state" placeholder="State" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['zip'] ? $child['zip'] : "" ?>" class="form-control" name="zip" placeholder="Zip Code" />
                        </div>
                      </div>
                      <?php /* parishes_select("parents-parish", "parents-parish", array("chosen-select"), $child['parents_parish'], "Parent Custody Parish"); */ ?>
                    </section>


                    <section>
                      <div class="row">
                        <h2 class="fs-title">Father</h2>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['father_name'] ? $child['father_name'] : "" ?>" class="form-control" name="father-first-name" placeholder="Father's First Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="text" value="<?php echo $child['father_middle'] ? $child['father_middle'] : "" ?>" class="form-control" name="father-middle-name" placeholder="Father's Middle Name" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['father_last'] ? $child['father_last'] : "" ?>" class="form-control" name="father-last-name" placeholder="Father's Last Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="email" value="<?php echo $child['father_email'] ? $child['father_email'] : "" ?>" class="form-control" name="father-email" placeholder="Father's Email" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="tel" value="<?php echo $child['father_phone'] ? $child['father_phone'] : ($current_user['phone'] ? $current_user['phone'] : ""); ?>" class="form-control" name="father-phone" placeholder="Father's Phone" />
                        </div>
                        <div class="form-group col-sm-6">
                          <label class="checkbox-inline">
                              <input type="checkbox" <?php echo $child['father_catholic'] == 1 ? "checked" : "" ?> name="father-catholic" id="father-catholic" value="1"> Catholic
                          </label>
                          <label class="checkbox-inline">
                              <input type="checkbox" <?php echo $child['father_id'] == 1 ? "checked" : "" ?> name="father-id" id="father-id" value="1"> ID
                          </label>
                        </div>
                      </div>
                    </section>


                    <section>
                      <div class="row">
                        <h2 class="fs-title">Mother</h2>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['mother_name'] ? $child['mother_name'] : "" ?>" class="form-control" name="mother-first-name" placeholder="Mother's First Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="text" value="<?php echo $child['mother_middle'] ? $child['mother_middle'] : "" ?>" class="form-control" name="mother-middle-name" placeholder="Mother's Middle Name" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['mother_last'] ? $child['mother_last'] : "" ?>" class="form-control" name="mother-last-name" placeholder="Mother's Last Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="email" value="<?php echo $child['mother_email'] ? $child['mother_email'] : "" ?>" class="form-control" name="mother-email" placeholder="Mother's Email" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="tel" value="<?php echo $child['mother_phone'] ? $child['mother_phone'] : "" ?>" class="form-control" name="mother-phone" placeholder="Mother's Phone" />
                        </div>
                        <div class="form-group col-sm-6">
                          <label class="checkbox-inline">
                              <input type="checkbox" <?php echo $child['mother_catholic'] == 1 ? "checked" : "" ?> name="mother-catholic" id="mother-catholic" value="1"> Catholic
                          </label>
                          <label class="checkbox-inline">
                              <input type="checkbox" <?php echo $child['mother_id'] == 1 ? "checked" : "" ?> name="mother-id" id="mother-id" value="1"> ID
                          </label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['mother_married_name'] ? $child['mother_married_name'] : "" ?>" class="form-control" name="mother-married-name" placeholder="Mother's Married Last Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <label class="checkbox-inline">
                              <input type="checkbox" <?php echo $child['mmn_birth_certificate'] == 1 ? "checked" : "" ?> name="mother-birth-certificate" id="mother-birth-certificate" value="1"> Birth Certificate
                          </label>
                        </div>
                      </div>
                    </section>


                    <div class="row">
                      <div class="form-group col-xs-6">
                        <input type="button" name="previous" class="previous action-button" value="Previous" />
                      </div>
                      <div class="form-group col-xs-6">
                        <input type="button" name="next" class="next action-button" value="Next" />
                      </div>
                    </div>

                </fieldset>

                <fieldset>

                    <h2 class="fs-title">Godparents</h2>
                    <h3 class="fs-subtitle">Enter the godparents' info</h3>


                    <section>
                      <div class="row">
                        <h2 class="fs-title">Godfather</h2>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['godfather_name'] ? $child['godfather_name'] : "" ?>" class="form-control" name="godfather-first-name" placeholder="Godather's First Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="text" value="<?php echo $child['godfather_middle'] ? $child['godfather_middle'] : "" ?>" class="form-control" name="godfather-middle-name" placeholder="Godather's Middle Name" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['godfather_last'] ? $child['godfather_last'] : "" ?>" class="form-control" name="godfather-last-name" placeholder="Godather's Last Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="email" value="<?php echo $child['godfather_email'] ? $child['godfather_email'] : "" ?>" class="form-control" name="godfather-email" placeholder="Godfather's Email" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input type="tel" value="<?php echo $child['godfather_phone'] ? $child['godfather_phone'] : "" ?>" class="form-control" name="godfather-phone" placeholder="Godfather's Phone" />
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['godfather_catholic'] == 1 ? "checked" : "" ?> name="godfather-catholic" id="godfather-catholic" value="1"> Catholic
                            </label>
                        </div>
                      </div>
                      <?php /* parishes_select("godfather-parish", "godfather-parish", array("chosen-select"), $child['godfather_parish'], "Godfather's Parish"); */ ?>
                    </section>

                    <section>
                      <div class="row">
                        <h2 class="fs-title">Godmother</h2>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['godmother_name'] ? $child['godmother_name'] : "" ?>" class="form-control" name="godmother-first-name" placeholder="Godmother's First Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="text" value="<?php echo $child['godmother_middle'] ? $child['godmother_middle'] : "" ?>" class="form-control" name="godmother-middle-name" placeholder="Godmother's Middle Name" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input required type="text" value="<?php echo $child['godmother_last'] ? $child['godmother_last'] : "" ?>" class="form-control" name="godmother-last-name" placeholder="Godmother's Last Name" />
                        </div>
                        <div class="form-group col-sm-6">
                          <input type="email" value="<?php echo $child['godmother_email'] ? $child['godmother_email'] : "" ?>" class="form-control" name="godmother-email" placeholder="Godmother's Email" />
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                          <input type="tel" value="<?php echo $child['godmother_phone'] ? $child['godmother_phone'] : "" ?>" class="form-control" name="godmother-phone" placeholder="Godmother's Phone" />
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['godmother_catholic'] == 1 ? "checked" : "" ?> name="godmother-catholic" id="godmother-catholic" value="1"> Catholic
                            </label>
                        </div>
                      </div>
                      <?php /* parishes_select("godmother-parish", "godmother-parish", array("chosen-select"), $child['godmother_parish'], "Godmother's Parish"); */ ?>
                    </section>


                    <div class="row">
                      <div class="form-group col-xs-6">
                        <input type="button" name="previous" class="previous action-button" value="Previous" />
                      </div>
                      <div class="form-group col-xs-6">
                        <input type="button" name="next" class="next action-button" value="Next" />
                      </div>
                    </div>

                </fieldset>

                <fieldset>

                    <h2 class="fs-title">Finish</h2>
                    <h3 class="fs-subtitle">Finish up and submit</h3>

                    <div class="row">
                      <div class="form-group col-xs-12">
                        <textarea class="form-control" name="note" placeholder="Notes"><?php echo $child['note'] ? $child['note'] : "" ?></textarea>
                      </div>
                    </div>


                    <div class="row">
                      <div class="form-group col-xs-6">
                        <input type="button" name="previous" class="previous action-button" value="Previous" />
                      </div>
                      <div class="form-group col-xs-6">
                        <input type="submit" name="register-submit" class="submit action-button" value="Submit" />
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
