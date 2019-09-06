<?php

namespace myfanclub\modules\log\view;

use DateTimeZone;
use Dubture\Monolog\Reader\LogReader;
use myfanclub\core\MyfcModuleView;

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
class MyfcLogView extends MyfcModuleView
{
    /**
     * Render MemberAdministration user interface
     *
     * @access public
     */
    public function render($logs)
    {
        ?>
        <div class="wrap">
            <div class="ui raised segment wp-fanclub-raised-segment">
                <?php $this->header(); ?>
                <h1 class="wp-fanclub-h1">Logs</h1>
                <hr class="wp-fanclub-hr"/>
                <table class="ui celled padded table">
                    <thead>
                    <tr>
                        <th>Zeitstempel</th>
                        <th>Nachricht</th>
                        <th>Loglevel</th>
                        <th>Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    if ($logs instanceof LogReader) {
                        foreach ($logs as $log) {
                            if (!empty($log)) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $log['date']->setTimezone(new DateTimeZone('Europe/Berlin'))->format('d.m.Y - H:i:s'); ?>
                                    </td>
                                    <td class="single line">
                                        <b><?= $log['message'] ?></b>
                                    </td>
                                    <td>
                                        <span class="ui blue label "><?= $log['level'] ?></span>
                                    </td>
                                    <td style="font-size: 10px; word-break: break-word;">
                                        <?php
                                        foreach ($log['context'] as $key => $value) {
                                            ?>
                                            <b><?= $key ?>:</b> <?= $value ?>,
                                            <?php
                                        } ?>
                                    </td>

                                </tr>
                                <?php
                            }
                        }
                    } else { ?>
                        <tr>
                            Bisher sind keine Logs vorhanden!
                        </tr>

                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr></tr>
                    </tfoot>
                </table>


            </div>
            <?php
            $this->footer(); ?>
            <!-- ==== -->
        </div>
        <?php
    }
}

?>