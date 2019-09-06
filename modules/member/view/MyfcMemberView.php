<?php

namespace myfanclub\modules\member\view;

use myfanclub\core\MyfcModuleView;
use myfanclub\modules\member\database\MyfcMemberDatabase;
use myfanclub\helper\MyfcFormat;
use myfanclub\helper\MyfcPreferenceData;
use myfanclub\core\MyfcCore;
use myfanclub\core\MyfcModuleCollector;

/**
 * MemberAdministration
 *
 * View class for MemberAdministration
 *
 *
 * @package  views
 * @author   Ultra Sites Medienagentur <info@ultra-sites.de>
 * @version  alpha
 * @access   public
 * @see      http://myfanclub.ultra-sites.de
 */
class MyfcMemberView extends MyfcModuleView
{
    private $currentUser;

    /**
     * @var MyfcMemberDatabase $database
     */
    private $database;


    /**
     * @var MyfcCore $core ;
     */
    private $core;

    public function __construct(MyfcMemberDatabase $database)
    {
        $this->database = $database;
        $this->currentUser = wp_get_current_user();
        $this->core = MyfcCore::getInstance();
    }

    public function renderExportPdf()
    {
        ?>
        <iframe style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"
                src="<?= plugins_url("../MyfcMemberExportPdf.php", __FILE__) ?>"></iframe>
        <?php
    }

    /**
     * Render MemberAdministration user interface
     *
     * @access public
     */
    public function render($request)
    {
        $data = $this->database->myfcSelectAllFromTable('lastname, forename ASC');

        $intersectionMembers = $this->database->myfcIntersectionOfWpUsersAndFanclubMembers();

        if (!empty($request['save'])) {
            $this->successAlert('Das Mitglied wurde erfolgreich hinzugefügt!');
        }

        if (!empty($request['delete'])) {
            $this->successAlert('Das Mitglied wurde erfolgreich entfernt!');
        }

        if (!empty($request['edit'])) {
            $this->successAlert('Die Mitgliedsdaten wurden geändert!');
        }

        if (!empty($request['mail'])) {
            $this->successAlert('Alle Mails wurden verschickt!');
        } ?>
        <div class="wrap">
        <div class="ui raised segment wp-fanclub-raised-segment">
            <?php $this->header(); ?>
            <h1 class="wp-fanclub-h1">Mitglieder</h1>
            <hr class="wp-fanclub-hr"/>
            <div class="ui grid">
                <div class="ten wide column">
                    <?php if ($this->currentUser->has_cap('myfc_app_member_add')) { ?>
                        <button class="ui icon button" trigger-modal="modalAdd">
                            Neues Mitglied
                            <i class="plus icon"></i>
                        </button>
                        <?php
                    } ?>
                    <a target="_blank"
                       href="<?= get_site_url() . '/wp-admin/admin.php?page=myfc_member&exportPdf=true'; ?>">
                        <button class="ui icon button">
                            Mitgliederliste drucken
                            <i class="print icon"></i>
                        </button>
                    </a>

                    <?php if ($this->core->isModuleRegistered('mail') && $this->currentUser->has_cap('myfc_app_member_mail')) { ?>
                        <button class="ui icon button" trigger-modal="modalMail">
                            Rundmail versenden
                            <i class="mail icon"></i>
                        </button>
                        <?php
                    }
                      ?>
                </div>
                <div class="six wide column">

                    <div class="ui icon fluid input">
                        <input type="text" id="memberSearch" placeholder="Mitgliedersuche...">
                        <i class="search  icon"></i>
                    </div>
                </div>
            </div>
            <br/>
            <table id="memberData" class="ui single line table"
            ">
            <thead>
            <tr>
                <th>Mitglieder&nbsp;&nbsp;&nbsp;&nbsp;<i class="users icon"></i><?= count($data) ?></th>
                <th></th>
            </tr>

            </thead>
            <tbody>
            <?php foreach ($data as $idx => $row) { ?>
                <tr>
                    <td><?php

                        global $wp_roles;

                        $cap_array = $this->database->myfcSelectUserCapability($row->id);
                        $cap = key(unserialize($cap_array['meta_value']));

                        $roleName = $wp_roles->roles[$cap]['name'];

                    ?>

                        <span class="wp-fanclub-members-data__fullname ui blue label wp-fanclub-font-size-em-09 wp-fanclub-badge-blue"
                              forename="<?= $row->forename ?>" lastname="<?= $row->lastname ?>">
                                <?= $row->forename; ?>
                                <?= $row->lastname; ?>
                            </span>

                        <?php if ($cap != 'associationMember') { ?>
                            <span class="wp-fanclub-members-data__fullname ui orange label wp-fanclub-font-size-em-09 wp-fanclub-badge-orange"><?= $roleName ?></span>
                        <?php } ?>

                        <div class="wp-fanclub-members-data__address">
                            <?= $row->street; ?>
                            <?= $row->housenumber; ?>,
                            <?= $row->plz; ?>
                            <?= $row->city; ?>
                        </div>
                        <div id="wp-fanclub-members-data__collapsible-<?= $idx ?>"
                             class="wp-fanclub-members-data__collapsible">
                            <div class="ui list wp-fanclub-members-data__font-size-small">
                                <div class="item">
                                    <i class="envelope icon"></i>
                                    <div class="content"><?= $row->email; ?></div>
                                </div>
                                <div class="item">
                                    <i class="phone icon"></i>
                                    <div class="content"><?= $row->phone; ?></div>
                                </div>
                                <div class="item">
                                    <i class="birthday cake icon"></i>
                                    <div class="content"><?= MyfcFormat::myfcFormatDateToEuropean($row->birthday); ?>
                                        (Alter: <?= MyfcFormat::myfcDateInYears($row->birthday) ?>)
                                    </div>
                                </div>
                                <div class="item">
                                    <i class="rocket icon"></i>
                                    <div class="content"><?= MyfcFormat::myfcFormatDateToEuropean($row->start); ?>
                                        (<?= MyfcFormat::myfcDateInYears($row->start) ?> Jahr/e)
                                    </div>
                                </div>
                                <div class="item">
                                    <span class="ui label">
                                        <i class="euro sign icon"></i>
                                        <?= number_format(
                                            MyfcPreferenceData::myfcCalculateAmount(
                                                MyfcModuleCollector::getInstance()->load('preferences')['PREFERENCES']['CONFIG'][1]->config,
                                                $row->payment_special,
                                                MyfcFormat::myfcDateInYears($row->birthday)
                                            ),
                                            2,
                                            ',',
                                            '.'
                                        ); ?></b>
                                           
                                    </span>
                                    <?php if (!empty($row->payment_special)) { ?>
                                        <span class="ui label">
                                            <?= $row->payment_special; ?>
                                        </span>
                                        <?php
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <button class="ui basic icon button wp-fanclub-members-data__collapsible-button"
                                data-target="wp-fanclub-members-data__collapsible-<?= $idx ?>">
                            Mehr
                            <i class="chevron down icon" aria-hidden="true"></i>
                        </button>
                    </td>
                    <td>

                        <div class="ui icon buttons wp-fanclub-right">
                            <?php if ($this->currentUser->has_cap('myfc_app_member_edit')) { ?>
                                <button class="ui button" trigger-modal="modalEdit-<?= $idx ?>"><i
                                            class="pencil icon"></i></button>
                            <?php } ?>
                            <?php if ($this->core->isModuleRegistered('mail') && $this->currentUser->has_cap('myfc_app_member_mail')) { ?>
                                <button class="ui button" trigger-modal="modalMessage-<?= $idx ?>"><i
                                            class="envelope icon"></i></button>
                                <?php
                            } ?>

                            <?php if ($this->currentUser->has_cap('myfc_app_member_delete')) { ?>
                                <button class="ui red button" trigger-modal="modalTrash-<?= $idx ?>"><i
                                            class="trash icon"></i></button>
                            <?php } ?>
                        </div>
                        <!-- Modal Edit -->
                        <div class="ui small modal" id="modalEdit-<?= $idx ?>">
                            <div class="header">Mitgliedsdaten ändern</div>
                            <div class="content">
                                <form
                                        action="<?= get_site_url() . '/wp-admin/admin.php?page=myfc_member&edit=true&id=' . $row->id; ?>"
                                        method="Post">

                                    <h4 class="ui horizontal divider header">Persönliche Daten von <span
                                                class="ui blue label wp-fanclub-badge-blue"><?= $row->forename . ' ' . $row->lastname; ?></span>
                                    </h4>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <div class="eight wide column">
                                                <div class="ui fluid input">
                                                    <input type="text" name="forename" placeholder="Vorname"
                                                           value="<?= $row->forename; ?>" required/>
                                                </div>
                                            </div>
                                            <div class="eight wide column">
                                                <div class="ui fluid input">
                                                    <input type="text" name="lastname" placeholder="Nachname"
                                                           value="<?= $row->lastname; ?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ui grid">
                                            <div class="fourteen wide column">
                                                <div class="ui fluid input">
                                                    <input type="text" name="street" placeholder="Straße"
                                                           value="<?= $row->street; ?>" required/>
                                                </div>
                                            </div>
                                            <div class="two wide column">
                                                <div class="ui fluid input">
                                                    <input type="text" name="housenumber" placeholder="Hnr."
                                                           value="<?= $row->housenumber; ?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ui grid">
                                            <div class="eight wide column">
                                                <div class="ui fluid input">
                                                    <input type="text" name="plz" placeholder="PLZ"
                                                           value="<?= $row->plz; ?>" required/>
                                                </div>
                                            </div>
                                            <div class="eight wide column">
                                                <div class="ui fluid input">
                                                    <input type="text" name="city" placeholder="Ort"
                                                           value="<?= $row->city; ?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ui grid">
                                            <div class="eight wide column">
                                                <div class="ui fluid input">
                                                    <input type="text" name="phone" placeholder="Telefon"
                                                           value="<?= $row->phone; ?>"/>
                                                </div>
                                            </div>
                                            <div class="eight wide column">
                                                <div class="ui calendar" id="editBirthday">
                                                    <div class="ui input fluid left icon">
                                                        <i class="calendar icon"></i>
                                                        <input type="text" placeholder="Geburtsdatum"
                                                               name="birthday"
                                                               value="<?= MyfcFormat::myfcFormatDateToEuropean($row->birthday); ?>"
                                                               required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ui grid">
                                            <div class="sixteen wide column">
                                                <div class="ui fluid input">
                                                    <input type="text" name="email" placeholder="Emailadresse"
                                                           value="<?= $row->email; ?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="ui horizontal divider header">Fanclubdaten von <span
                                                class="ui blue label wp-fanclub-badge-blue"><?= $row->forename . ' ' . $row->lastname; ?>
                                    </h4>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <div class="eight wide column">

                                                    <div class="ui calendar" id="editStart">
                                                        <div class="ui input fluid left icon">
                                                            <i class="calendar icon"></i>
                                                            <input type="text" placeholder="Fanclubeintritt"
                                                                   name="start"
                                                                   value="<?= MyfcFormat::myfcFormatDateToEuropean($row->start); ?>"
                                                                   required>
                                                        </div>
                                                    </div>

                                            </div>
                                            <div class="eight wide column">
                                                <?php
                                                $paymentSpecials = MyfcPreferenceData::myfcGetPaymentSpecials(MyfcModuleCollector::getInstance()->load('preferences')['PREFERENCES']['CONFIG'][1]->config);
                                                if (count($paymentSpecials) > 0) {
                                                    ?>
                                                    <div class="ui fluid selection dropdown">
                                                        <input type="hidden" name="paymentSpecial"
                                                               tabindex="-1"/>
                                                        <i class="dropdown icon"></i>
                                                        <div class="default text">Beitragssonderstellung</div>
                                                        <div class="menu">
                                                            <?php foreach ($paymentSpecials as $paymentSpecial) { ?>
                                                                <div class="item"
                                                                     data-value="<?= $paymentSpecial->description ?>"><?= $paymentSpecial->description ?>
                                                                    (<?= number_format($paymentSpecial->amount, 2, ',', '.') ?>&euro;)
                                                                </div>
                                                                <?php
                                                            } ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <button type="submit" class="ui approve green icon button">Ändern <i
                                                    class="pencil icon"></i></button>
                                        <div class="ui cancel button">Abbrechen</div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- ==== -->
                        <!-- Modal Message -->
                        <?php if ($this->core->isModuleRegistered('mail')) { ?>
                            <div class="ui small modal" id="modalMessage-<?= $idx ?>">
                                <div class="header">Direktnachricht versenden</div>
                                <div class="content">

                                    <form action="<?= get_site_url() . '/wp-admin/admin.php?page=myfc_member&mail=message&id=' . $row->id ?>"
                                          method="Post">
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class="sixteen wide column">
                                                    <div class="ui fluid labeled input">
                                                        <div class="ui label">Empfänger</div>
                                                        <div class="ui blue label wp-fanclub-font-size-em-09 wp-fanclub-badge-blue"><?= $row->forename . ' ' . $row->lastname ?></div>
                                                    </div>
                                                </div>
                                                <div class="sixteen wide column">
                                                    <div class="ui fluid labeled input">
                                                        <div class="ui label">Betreff</div>
                                                        <input type="text" name="mail_subject"
                                                               placeholder="Betreff" required/>
                                                    </div>
                                                </div>
                                                <div class="sixteen wide column">
                                                    <div class="ui fluid left labeled input">
                                                        <div class="ui label">
                                                            <i class="envelope icon"></i>
                                                        </div>
                                                        <textarea rows="10" name="mail_message"
                                                                  style="width: 100%; white-space: pre-wrap;"
                                                                  wrap="hard"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="submit" class="ui fluid green button"
                                               value="Nachricht versenden"/>
                                    </form>


                                </div>
                            </div>
                        <?php } ?>
                        <!-- ==== -->
                        <!-- Modal Trash -->
                        <div class="ui small modal" id="modalTrash-<?= $idx ?>">
                            <div class="header">Mitglied entfernen</div>
                            <div class="content">
                                <form class="form ui"
                                      action="<?= get_site_url() . '/wp-admin/admin.php?page=myfc_member&delete=true&id=' . $row->id; ?>"
                                      method="Post">
                                    <h4 class="modal-title" id="myModalLabel">Möchten Sie dieses
                                        Mitglied
                                        wirklich löschen?</h4>

                                    <div class="ui secondary segment">
                                        <div class="ui blue label wp-fanclub-font-size-em-09 wp-fanclub-badge-blue"><?= $row->forename ?> <?= $row->lastname ?></div>
                                        <div class="wp-fanclub-members-data__address"><?= $row->street ?> <?= $row->housenumber ?>
                                            , <?= $row->plz ?> <?= $row->city ?></div>
                                    </div>

                                    <?php if ($this->core->isModuleRegistered('mail')) { ?>
                                        <div class="ui test toggle checkbox">
                                            <input type="checkbox" checked="checked" name="sendAdditionalData">
                                            <label>Austrittsbestätigung per Email an das Mitglied
                                                senden.</label>
                                        </div>
                                        <br/><br/>
                                        <?php
                                    } ?>

                                    <div class="actions">
                                        <button type="submit" class="ui approve red icon button">Löschen <i
                                                    class="trash icon"></i></button>
                                        <div class="ui cancel button">Abbrechen</div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- ==== -->
                    </td>
                </tr>
                <?php
            } ?>
            </tbody>
            </table>
            <!-- Modal Add -->
            <div class="ui small modal" id="modalAdd">
                <div class="header">Neues Mitglied hinzufügen</div>
                <div class="content">
                    <form class="ui form"
                          action="<?= get_site_url() . '/wp-admin/admin.php?page=myfc_member&save=true'; ?>"
                          method="Post">
                        <div class="ui secondary segment">
                            Verfügbare Wordpress-User, die verknüpft werden können:<br/>
                            <?php

                            $availableWordpressUser = count($intersectionMembers) > 0; ?>
                            <?php if ($availableWordpressUser) { ?>
                                <div class="ui fluid selection dropdown">
                                    <input type="hidden" name="intersectionMember" tabindex="-1"/>
                                    <i class="dropdown icon"></i>
                                    <div class="default text">Registrierte Benutzer</div>
                                    <div class="menu">
                                        <?php foreach ($intersectionMembers as $row) { ?>
                                            <div class="item"
                                                 data-value="<?= $row->ID ?>"><?= $row->display_name ?></div>
                                            <?php
                                        } ?>
                                    </div>
                                </div>
                                <?php
                            } else { ?>
                                <p><b>- keine WordPress User vorhanden -</b></p>
                                <?php
                            } ?>

                        </div>
                        <?php if ($availableWordpressUser) { ?>
                        <div id="wp-fanclub-modalAdd-data">
                            <h4 class="ui horizontal divider header">Persönliche Daten</h4>
                            <div class="ui segment">
                                <div class="ui grid">
                                    <div class="fourteen wide column">
                                        <div class="ui fluid input">
                                            <input type="text" name="street" placeholder="Straße" required/>
                                        </div>
                                    </div>
                                    <div class="two wide column">
                                        <div class="ui fluid input">
                                            <input type="text" name="housenumber" placeholder="Hnr." required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="ui grid">
                                    <div class="eight wide column">
                                        <div class="ui fluid input">
                                            <input type="text" name="plz" placeholder="PLZ" required/>
                                        </div>
                                    </div>
                                    <div class="eight wide column">
                                        <div class="ui fluid input">
                                            <input type="text" name="city" placeholder="Ort" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="ui grid">
                                    <div class="eight wide column">
                                        <div class="ui fluid input">
                                            <input type="text" name="phone" placeholder="Telefon"/>
                                        </div>
                                    </div>
                                    <div class="eight wide column">
                                        <div class="ui calendar" id="addBirthday">
                                            <div class="ui input fluid left icon">
                                                <i class="calendar icon"></i>
                                                <input type="text" placeholder="Geburtsdatum" name="birthday" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="ui horizontal divider header">Fanclubdaten</h4>
                            <div class="ui segment">
                                <div class="ui grid">
                                    <div class="eight wide column">

                                        <div class="ui calendar" id="addStart">
                                            <div class="ui input fluid left icon">
                                                <i class="calendar icon"></i>
                                                <input type="text" placeholder="Fanclubeintritt" name="start" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="eight wide column">
                                        <?php
                                        $paymentSpecials = MyfcPreferenceData::myfcGetPaymentSpecials(MyfcModuleCollector::getInstance()->load('preferences')['PREFERENCES']['CONFIG'][1]->config);
                                        if (count($paymentSpecials) > 0) {
                                            ?>
                                            <div class="ui fluid selection dropdown">
                                                <input type="hidden" name="paymentSpecial" tabindex="-1"/>
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Beitragssonderstellung</div>
                                                <div class="menu">
                                                    <?php foreach ($paymentSpecials as $paymentSpecial) { ?>
                                                        <div class="item"
                                                             data-value="<?= $paymentSpecial->description ?>"><?= $paymentSpecial->description ?>
                                                            (<?= number_format($paymentSpecial->amount, 2, ',', '.') ?>&euro;)
                                                        </div>
                                                        <?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <?php
                                        } ?>
                                    </div>
                                    <?php if ($this->core->isModuleRegistered('mail')) { ?>
                                        <div class="sixteen wide column">
                                            <div class="ui test toggle checkbox">
                                                <input type="checkbox" checked="checked" name="sendAdditionalData">
                                                <label>Eintrittsbestätigung per Email an das Neumitglied senden.</label>
                                            </div>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                            </div>
                            <input type="submit" class="ui fluid green button" value="Mitglied eintragen"/>

                        <?php } ?>
                        </div>


                    </form>
                </div>
            </div>
            <!-- Modal Roundmail -->
            <?php if ($this->core->isModuleRegistered('mail') && $this->currentUser->has_cap('myfc_app_member_mail')) { ?>
                <div class="ui small modal" id="modalMail">
                    <div class="header">Rundmail versenden</div>
                    <div class="content">

                        <form action="<?= get_site_url() . '/wp-admin/admin.php?page=myfc_member&mail=queue' ?>"
                              method="Post">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <div class="sixteen wide column">
                                        <div class="ui fluid labeled input">
                                            <div class="ui label">Empfänger</div>
                                            <div class="ui blue label wp-fanclub-font-size-em-09 wp-fanclub-badge-blue">
                                                Alle
                                                Mitglieder
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sixteen wide column">
                                        <div class="ui fluid labeled input">
                                            <div class="ui label">Betreff</div>
                                            <input type="text" name="mail_subject" placeholder="Betreff" required/>
                                        </div>
                                    </div>
                                    <div class="sixteen wide column">
                                        <div class="ui fluid left labeled input">
                                            <div class="ui label">
                                                <i class="envelope icon"></i>
                                            </div>
                                            <textarea name="mail_message" rows="10" style="width: 100%;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" class="ui fluid green button" value="Rundmail versenden"/>
                        </form>


                    </div>
                </div>
            <?php }

            $this->footer(); ?>
            <!-- ==== -->
        </div>
        <?php
    }
}

?>