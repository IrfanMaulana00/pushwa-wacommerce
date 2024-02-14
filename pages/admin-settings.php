<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-2 well">
                <div class="col-sm-12 form-legend">
                    <h2>PushWa Settings</h2>
                </div>
                <div class="col-sm-12 form-column">
                    <form enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label for="pushwa_token">PushWa Token</label>
                            <input type="text" id="pushwa_token" name="pushwa_token" class="form-control form-control-sm" value="<?= get_option('pushwa_token'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="pushwa_admin_number">Admin Phone Number</label>
                            <input type="number" id="pushwa_admin_number" name="pushwa_admin_number" class="form-control form-control-sm" value="<?= get_option('pushwa_admin_number'); ?>" placeholder="628xxx" required>
                        </div>
                        <div class="form-group">
                            <label for="pushwa_msg_new_order">New Order Message</label>
                            <textarea name="pushwa_msg_new_order" id="pushwa_msg_new_order" cols="30" rows="4" class="form-control form-control-sm"><?= get_option('pushwa_msg_new_order'); ?></textarea>
                            <small class="form-text text-muted">
                                Keyword : {detail}, {payment_method}, {name}, {order_id}, {amount}, {date}, {payment}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="pushwa_msg_processing">Processing Message</label>
                            <textarea name="pushwa_msg_processing" id="pushwa_msg_processing" cols="30" rows="4" class="form-control form-control-sm"><?= get_option('pushwa_msg_processing'); ?></textarea>
                            <small class="form-text text-muted">
                                Keyword : {name}, {order_id}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="pushwa_msg_completed">Completed Message</label>
                            <textarea name="pushwa_msg_completed" id="pushwa_msg_completed" cols="30" rows="4" class="form-control form-control-sm"><?= get_option('pushwa_msg_completed'); ?></textarea>
                            <small class="form-text text-muted">
                                Keyword : {name}, {order_id}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="pushwa_msg_cancel">Cancel Message</label>
                            <textarea name="pushwa_msg_cancel" id="pushwa_msg_cancel" cols="30" rows="4" class="form-control form-control-sm"><?= get_option('pushwa_msg_cancel'); ?></textarea>
                            <small class="form-text text-muted">
                                Keyword : {name}, {order_id}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="pushwa_msg_refunded">Refunded Message</label>
                            <textarea name="pushwa_msg_refunded" id="pushwa_msg_refunded" cols="30" rows="4" class="form-control form-control-sm"><?= get_option('pushwa_msg_refunded'); ?></textarea>
                            <small class="form-text text-muted">
                                Keyword : {name}, {order_id}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="pushwa_msg_failed">Failed Message</label>
                            <textarea name="pushwa_msg_failed" id="pushwa_msg_failed" cols="30" rows="4" class="form-control form-control-sm"><?= get_option('pushwa_msg_failed'); ?></textarea>
                            <small class="form-text text-muted">
                                Keyword : {name}, {order_id}
                            </small>
                        </div>
                        <input type="submit" value="Save" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>