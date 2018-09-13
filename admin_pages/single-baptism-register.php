<?php
global $wpdb;
$table_name = $wpdb->prefix . 'baptism_registers';
$id = intval($_GET['registry']);

$results = $wpdb->get_results(
    sprintf(
        "SELECT *
        FROM %s
        WHERE id = '$id'",
        $table_name
    ),
    ARRAY_A
);
$child = $results[0];
// print_r($child);
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Edit Baptism Registry' . $screen->id, ET_DOMAIN); ?></h1>
    <hr class="wp-header-end">
    <div id="lost-connection-notice" class="error hidden">
        <p><span class="spinner"></span> <strong>Connection lost.</strong> Saving has been disabled until you’re reconnected.	<span class="hide-if-no-sessionstorage">We’re backing up this post in your browser, just in case.</span>
        </p>
    </div>

    <?php
    if ( isset($_GET['updated']) ) {
        $updated = intval($_GET['updated']);

        if ( $updated > 0 ) {
            ?>
            <div class="alert alert-success" role="alert">
                Registry updated!
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-warning" role="alert">
                No fields were updated. This may mean you didn't change any values or that an error ocurred. If it was the latter, please try again.
            </div>
            <?php
        }
    }
    ?>

    <form method="post" id="single-registry-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
        <?php wp_nonce_field('placita_edit_single_registry'); ?>
        <input type="hidden" name="action" value="edit_single_registry">
        <input type="hidden" name="registry" value="<?php echo $id; ?>">

        <!-- Child -->
        <fieldset>

            <h2 class="fs-title">Child</h2>

            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="first_name">First Name</label>
                    <input required type="text" class="form-control" value="<?php echo $child['first_name'] ? $child['first_name'] : "" ?>" name="first_name" id="first_name" placeholder="First Name" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" class="form-control" value="<?php echo $child['middle_name'] ? $child['middle_name'] : "" ?>" name="middle_name" id="middle_name" placeholder="Middle Name" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="last_name">Last Name</label>
                    <input required type="text" class="form-control" value="<?php echo $child['last_name'] ? $child['last_name'] : "" ?>" name="last_name" id="last_name" placeholder="Last Name" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="birthdate">Birthdate</label>
                    <input required type="text" value="<?php echo $child['birthdate'] ? date("m/d/Y", strtotime($child['birthdate'])) : "" ?>" class="form-control date birthdate" name="birthdate" id="birthdate" placeholder="Birthdate" />
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="birthplace">Birthplace</label>
                    <input required type="text" value="<?php echo $child['birthplace'] ? $child['birthplace'] : "" ?>" class="form-control" name="birthplace" id="birthplace" placeholder="Birthplace" />
                </div>
                <div class="form-group col-sm-6">
                    <label for="gender">Gender</label>
                    <div class="form-check">
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

        </fieldset>

        <!-- Parents -->
        <fieldset>


            <section>
                <h2 class="fs-title">Parents</h2>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="">Married</label>
                        <div class="form-check">
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['parents_married'] == 1 ? "checked" : "" ?> name="parents_married" id="parents-married" value="1"> Married
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['parents_married_church'] == 1 ? "checked" : "" ?> name="parents_married_church" id="parents-married-church" value="1"> Married in Church
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="contact_email">Contact Email</label>
                        <input required type="email" value="<?php echo $child['contact_email'] ? $child['contact_email'] : "" ?>" class="form-control" name="contact_email" placeholder="Contact Email" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="address">Street Address</label>
                            <input required type="text" value="<?php echo $child['address'] ? $child['address'] : "" ?>" class="form-control" name="address" placeholder="Street Address" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="city">City</label>
                        <input required type="text" value="<?php echo $child['city'] ? $child['city'] : "" ?>" class="form-control" name="city" placeholder="City" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="state">State</label>
                        <input required type="text" value="<?php echo $child['state'] ? $child['state'] : "" ?>" class="form-control" name="state" placeholder="State" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="zip">Zip</label>
                        <input required type="text" value="<?php echo $child['zip'] ? $child['zip'] : "" ?>" class="form-control" name="zip" placeholder="Zip Code" />
                    </div>
                </div>
            </section>


            <section>
                <div class="row">
                    <div class="col-12"><h3 class="fs-title">Father</h3></div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="father_name">Father Name</label>
                        <input required type="text" value="<?php echo $child['father_name'] ? $child['father_name'] : "" ?>" class="form-control" name="father_name" placeholder="Father's First Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="father_middle">Father Middle Name</label>
                        <input type="text" value="<?php echo $child['father_middle'] ? $child['father_middle'] : "" ?>" class="form-control" name="father_middle" placeholder="Father's Middle Name" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="father_last">Father Last Name</label>
                        <input required type="text" value="<?php echo $child['father_last'] ? $child['father_last'] : "" ?>" class="form-control" name="father_last" placeholder="Father's Last Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="father_email">Father Email</label>
                        <input type="email" value="<?php echo $child['father_email'] ? $child['father_email'] : "" ?>" class="form-control" name="father_email" placeholder="Father's Email" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="father_phone">Father Phone</label>
                        <input required type="tel" value="<?php echo $child['father_phone'] ? $child['father_phone'] : ($current_user['phone'] ? $current_user['phone'] : ""); ?>" class="form-control" name="father_phone" placeholder="Father's Phone" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for=""></label>
                        <div class="form-check">
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['father_catholic'] == 1 ? "checked" : "" ?> name="father_catholic" id="father-catholic" value="1"> Catholic
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['father_id'] == 1 ? "checked" : "" ?> name="father_id" id="father-id" value="1"> ID
                            </label>
                        </div>
                    </div>
                </div>
            </section>


            <section>
                <div class="row">
                    <div class="col-12"><h3 class="fs-title">Mother</h3></div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="mother_name">Mother Name</label>
                        <input required type="text" value="<?php echo $child['mother_name'] ? $child['mother_name'] : "" ?>" class="form-control" name="mother_name" placeholder="Mother's First Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="mother_middle">Mother Middle Name</label>
                        <input type="text" value="<?php echo $child['mother_middle'] ? $child['mother_middle'] : "" ?>" class="form-control" name="mother_middle" placeholder="Mother's Middle Name" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="mother_last">Mother Last Name</label>
                        <input required type="text" value="<?php echo $child['mother_last'] ? $child['mother_last'] : "" ?>" class="form-control" name="mother_last" placeholder="Mother's Last Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="mother_email">Mother Email</label>
                        <input type="email" value="<?php echo $child['mother_email'] ? $child['mother_email'] : "" ?>" class="form-control" name="mother_email" placeholder="Mother's Email" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="mother_phone">Mother Phone</label>
                        <input required type="tel" value="<?php echo $child['mother_phone'] ? $child['mother_phone'] : "" ?>" class="form-control" name="mother_phone" placeholder="Mother's Phone" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for=""></label>
                        <div class="form-check">
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['mother_catholic'] == 1 ? "checked" : "" ?> name="mother_catholic" id="mother-catholic" value="1"> Catholic
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['mother_id'] == 1 ? "checked" : "" ?> name="mother_id" id="mother-id" value="1"> ID
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="mother_married_name">Mother Maried Name</label>
                        <input required type="text" value="<?php echo $child['mother_married_name'] ? $child['mother_married_name'] : "" ?>" class="form-control" name="mother_married_name" placeholder="Mother's Married Last Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for=""></label>
                        <div class="form-check">
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['mmn_birth_certificate'] == 1 ? "checked" : "" ?> name="mmn_birth_certificate" id="mother-birth-certificate" value="1"> Birth Certificate
                            </label>
                        </div>
                    </div>
                </div>
            </section>

        </fieldset>

        <!-- Godparents -->
        <fieldset>

            <h2 class="fs-title">Godparents</h2>


            <section>
                <div class="row">
                    <div class="col-12"><h3 class="fs-title">Godfather</h3></div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="godfather_name">Godfather Name</label>
                        <input required type="text" value="<?php echo $child['godfather_name'] ? $child['godfather_name'] : "" ?>" class="form-control" name="godfather_name" placeholder="Godather's First Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="godfather_middle">Godfather Middle Name</label>
                        <input type="text" value="<?php echo $child['godfather_middle'] ? $child['godfather_middle'] : "" ?>" class="form-control" name="godfather_middle" placeholder="Godather's Middle Name" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="godfather_last">Godfather Last Name</label>
                        <input required type="text" value="<?php echo $child['godfather_last'] ? $child['godfather_last'] : "" ?>" class="form-control" name="godfather_last" placeholder="Godather's Last Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="godfather_email">Godfather Email</label>
                        <input type="email" value="<?php echo $child['godfather_email'] ? $child['godfather_email'] : "" ?>" class="form-control" name="godfather_email" placeholder="Godfather's Email" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="godfather_phone">Godfather Phone</label>
                        <input type="tel" value="<?php echo $child['godfather_phone'] ? $child['godfather_phone'] : "" ?>" class="form-control" name="godfather_phone" placeholder="Godfather's Phone" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for=""></label>
                        <div class="form-check">
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['godfather_catholic'] == 1 ? "checked" : "" ?> name="godfather_catholic" id="godfather-catholic" value="1"> Catholic
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="row">
                    <div class="col-12"><h3 class="fs-title">Godmother</h3></div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="godmother_name">Godmother Name</label>
                        <input required type="text" value="<?php echo $child['godmother_name'] ? $child['godmother_name'] : "" ?>" class="form-control" name="godmother_name" placeholder="Godmother's First Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="godmother_middle">Godmother Middle Name</label>
                        <input type="text" value="<?php echo $child['godmother_middle'] ? $child['godmother_middle'] : "" ?>" class="form-control" name="godmother_middle" placeholder="Godmother's Middle Name" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="godmother_last">Godmother Last Name</label>
                        <input required type="text" value="<?php echo $child['godmother_last'] ? $child['godmother_last'] : "" ?>" class="form-control" name="godmother_last" placeholder="Godmother's Last Name" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="godmother_email">Godmother Email</label>
                        <input type="email" value="<?php echo $child['godmother_email'] ? $child['godmother_email'] : "" ?>" class="form-control" name="godmother_email" placeholder="Godmother's Email" />
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="godmother_phone">Godmother Phone</label>
                        <input type="tel" value="<?php echo $child['godmother_phone'] ? $child['godmother_phone'] : "" ?>" class="form-control" name="godmother_phone" placeholder="Godmother's Phone" />
                    </div>
                    <div class="form-group col-sm-6">
                        <label for=""></label>
                        <div class="form-check">
                            <label class="checkbox-inline">
                                <input type="checkbox" <?php echo $child['godmother_catholic'] == 1 ? "checked" : "" ?> name="godmother_catholic" id="godmother-catholic" value="1"> Catholic
                            </label>
                        </div>
                    </div>
                </div>
            </section>

        </fieldset>

        <!-- Notes -->
        <fieldset>

            <div class="row">
                <div class="form-group col-12">
                    <label for="note">Notes</label>
                    <textarea class="form-control" name="note" placeholder="Notes"><?php echo $child['note'] ? $child['note'] : "" ?></textarea>
                </div>
            </div>

        </fieldset>

        <div class="row">
            <div class="form-group col-6 offset-3">
                <input type="submit" name="register-submit" class="btn btn-primary btn-block" value="Save" />
            </div>
        </div>

    </form>

    
    <form method="post" id="delete-single-registry" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
        <?php wp_nonce_field('placita_delete_single_registry'); ?>
        <input type="hidden" name="action" value="delete_single_registry">
        <input type="hidden" name="registry" value="<?php echo $id; ?>">
        <div class="row">
            <div class="form-group col-6 offset-3 text-center">
                <p class="mt-2">Or...</p>
                <input type="submit" name="delete-registry" class="btn btn-danger" value="Delete Registry" />
            </div>
        </div>
    </form>
</div>