<?php
    $custpricejson = json_decode(file_get_contents($config->companyfiles."json/custpricecodetbl.json"), true);
    $pricecodes = array();
    foreach ($custpricejson['data'] as $key => $code) {
        $pricecodes[$key] = $code['custpricecodedesc'];
    }

    $salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
    $salespeople = array();
    foreach ($salespersonjson['data'] as $salesperson) {
        $salespeople[$salesperson['splogin']] = $salesperson['spname'];
    }

    $changesalesrep = $users->find("name=$user->loginid")->count ? ($users->get("name=$user->loginid")->hasRole('manager')) : false;

    $requestmethod = $input->requestMethod('POST') ? 'post' : 'get';
    $custID = $input->$requestmethod->text('custID');
    $customer = Customer::load($custID);
?>

<div class="form-group">
    <a href="<?= $config->pages->customer.'cust-info/' . $custID; ?>" class="btn btn-primary">
        <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&ensp;Back to <?= $customer->name; ?> Page
    </a>
</div>
<form action="<?= $config->pages->customer.'redir/'; ?>" method="post">
    <input type="hidden" name="action" value="add-customer">
    <input type="hidden" name="salesperson2" value="">
    <input type="hidden" name="salesperson3" value="">
    <div class="row">
        <div class="col-sm-6">
           <legend>Bill-To</legend>
            <table class="table table-striped table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td class="control-label">Customer</td>
                        <td><p class="form-control-static">NEW CUSTOMER</p></td>
                    </tr>
                    <tr>
                        <td class="control-label">Name</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-name" value="<?= $customer->name; ?>"></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-address" value="<?= $customer->addr1; ?>"></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address 2</td>
                        <td><input type="text" class="form-control input-sm" name="billto-address2" value="<?= $customer->addr2; ?>"></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address 3</td>
                        <td><input type="text" class="form-control input-sm" name="billto-address3" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">City</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-city" value="<?= $customer->city; ?>"></td>
                    </tr>
                    <tr>
                        <td class="control-label">State</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-state" value="<?= $customer->state; ?>"></td>
                    </tr>
                    <tr>
                        <td class="control-label">Zip</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-zip" value="<?= $customer->zip; ?>"></td>
                    </tr>
                    <tr>
                        <td class="control-label">Country</td>
                        <td>
                            <?php $countries = get_countries(); ?>
                            <select name="billto-country" class="form-control input-sm">
                                <option value="USA">United States</option>
                                <?php foreach ($countries as $country) : ?>
                                    <option value="<?= $country['ccode']; ?>"><?= $country['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
           </table>
        </div> <!-- end bill to column -->

        <div class="col-sm-6">
           <legend>Ship-To</legend>
            <table class="table table-striped table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td class="control-label">Name</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-name" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-address" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address 2</td>
                        <td><input type="text" class="form-control input-sm" name="shipto-address2" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address 3</td>
                        <td><input type="text" class="form-control input-sm" name="shipto-address3" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">City</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-city" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">State</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-state" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Zip</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-zip" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Country</td>
                        <td>
                            <?php $countries = get_countries(); ?>
                            <select name="shipto-country" class="form-control input-sm">
                                <option value="USA">United States</option>
                                <?php foreach ($countries as $country) : ?>
                                    <option value="<?= $country['ccode']; ?>"><?= $country['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
           </table>
        </div> <!-- end shit to column -->
    </div> <!-- end top row-->
    <br>
    <div class="row">
        <div class="col-xs-6">
            <legend>Contact Information</legend>
            <table class="table table-striped table-bordered table-condensed">
                <tr>
                    <td class="control-label">Contact</td>
                    <td><input type="text" class="form-control input-sm" name="contact-name" value="<?= $customer->contact; ?>"></td>
                </tr>
                <tr>
                    <td class="control-label">Contact Title</td>
                    <td><input type="text" class="form-control input-sm" name="contact-title" value=""></td>
                </tr>
                <tr>
                    <td class="control-label">Phone</td>
                    <td><input type="tel" class="form-control input-sm phone-input" name="contact-phone" value="<?= $customer->phone; ?>"></td>
                </tr>
                <tr>
                    <td class="control-label">Ext.</td>
                    <td><input type="tel" class="form-control input-sm qty pull-right" name="contact-ext" value=""></td>
                </tr>
                <tr>
                    <td class="control-label">Fax</td>
                    <td><input type="tel" class="form-control input-sm phone-input" name="contact-fax" value=""></td>
                </tr>
                <tr>
                    <td class="control-label">E-mail</td>
                    <td><input type="email" class="form-control input-sm" name="contact-email" value="<?= $customer->email; ?>"></td>
                </tr>
                <tr>
                    <td class="control-label">AR Contact</td>
                    <td>
                        <?= $page->bootstrap->select('class=form-control input-sm|name=arcontact', array_flip($config->yesnoarray), 'N'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="control-label">Dunning Contact</td>
                    <td>
                        <?= $page->bootstrap->select('class=form-control input-sm|name=dunningcontact', array_flip($config->yesnoarray), 'N'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="control-label">Buying Contact</td>
                    <td>
                        <?= $page->bootstrap->select('class=form-control input-sm|name=buycontact', $config->buyertypes, 'N'); ?>
                    </td>
                </tr>
                <tr>
                    <?php if ($config->cptechcustomer == 'stat') : ?>
                        <td class="control-label">End User</td>
                    <?php else : ?>
                        <td class="control-label">Certificate Contact</td>
                    <?php endif; ?>
                    <td>
                        <?= $page->bootstrap->select('class=form-control input-sm|name=certcontact', array_flip($config->yesnoarray), 'N'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="control-label">Acknowledgement Contact</td>
                    <td>
                        <?= $page->bootstrap->select('class=form-control input-sm|name=ackcontact', array_flip($config->yesnoarray), 'N'); ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-xs-6">
            <legend>Salesperson Information</legend>
            <table class="table table-striped table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td class="control-label">Salesperson1</td>
                        <?php if ($config->cptechcustomer == 'stat' && $changesalesrep === false) : ?>
                            <td>
                                <?= $salespeople[$user->loginid]; ?>
                                <?= $page->bootstrap->input("name=salesperson1|type=hidden|value=$user->salespersonid"); ?>
                            </td>
                        <?php else : ?>
                            <td>
                                <?= $page->bootstrap->select('name=salesperson1|class=form-control input-sm', $salespeople, $user->salespersonid); ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td class="control-label">Price Code</td>
                        <td>
                            <?php $defaultcode = $pages->get('/config/')->default_pricecode; ?>
                            <?= isset($pricecodes[$defaultcode]) ? $pricecodes[$defaultcode] : 'No Default Code'; ?>
                            <?= $page->bootstrap->input("name=pricecode|type=hidden|value=$defaultcode"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="control-label">Notes</td>
                        <td>
                            <textarea name="notes" rows="4" class="form-control input-sm"></textarea>
                        </td>
                    </tr>
                </tbody>
           </table>
        </div>
    </div> <!-- end price/notes row -->
    <button type="submit" class="btn btn-primary">Add New Customer from Prospect&ensp;<i class="fa fa-user-plus" aria-hidden="true"></i></button>
</form>
