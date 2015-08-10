
            <a name="automated_metrics" class="anchor-point"></a>
            <h3 id="automated-metrics-heading">Automated Metrics <a class="info-icon" href="<?php echo site_url('docs'); ?>#automated_metrics"><span class="glyphicon glyphicon-info-sign"></span></a></h3>


            <?php if (empty($office_campaign->bureaudirectory_status) && empty($office_campaign->governanceboard_status)): ?>
                <p>No automated metrics are currently available for this milestone</p>
            <?php else: ?>
                <p>These metrics are generated by an automated analysis that runs every 24 hours until the end of the quarter at which point they become a historical snapshot</p>
            <?php endif; ?>



            <?php if (!empty($office_campaign->bureaudirectory_status)): ?>
                <a name="pdl_bureaudirectory" class="anchor-point"></a>
            <?php endif; ?>


            <?php if (!empty($office_campaign->bureaudirectory_status)): ?>

                <div id="bureaudirectory-heading" class="panel panel-default">
                    <div class="panel-heading">
                        /digitalstrategy/bureaudirectory.json
                        <a class="info-icon" href="<?php echo site_url('docs') . '#bureaudirectory' ?>">
                            <span class="glyphicon glyphicon-info-sign"></span>
                        </a>
                    </div>

                    <table class="table table-striped table-hover">

                        <tr>
                            <th>Expected bureaudirectory.json URL</th>
                            <td>
                                <?php if (!empty($office_campaign->bureaudirectory_status->expected_url)): ?>
                                    <a href="<?php echo $office_campaign->bureaudirectory_status->expected_url ?>"><?php echo $office_campaign->bureaudirectory_status->expected_url ?></a>
                                <?php endif; ?>

                                <?php
                                $http_code = (!empty($office_campaign->bureaudirectory_status->http_code)) ? $office_campaign->bureaudirectory_status->http_code : 0;

                                switch ($http_code) {
                                    case 404:
                                        $status_color = 'danger';
                                        break;
                                    case 200:
                                        $status_color = 'success';
                                        break;
                                    case 0:
                                        $status_color = '';
                                        break;
                                    default:
                                        $status_color = 'warning';
                                }

                                if (!empty($office_campaign->bureaudirectory_status->content_type)) {
                                    if (strpos($office_campaign->bureaudirectory_status->content_type, 'application/json') !== false) {
                                        $mime_color = 'success';
                                    } else {
                                        $mime_color = 'danger';
                                    }
                                } else {
                                    $mime_color = 'danger';
                                }
                                ?>

                            </td>
                        </tr>

                        <tr>
                            <th>Resolved bureaudirectory.json URL</th>
                            <td>
                                <a href="<?php echo $office_campaign->bureaudirectory_status->url ?>"><?php echo $office_campaign->bureaudirectory_status->url ?></a>
                            </td>
                        </tr>

                        <tr>
                            <th>Redirects</th>
                            <td>
                                <?php if (!empty($office_campaign->bureaudirectory_status->redirect_count)): ?>
                                                            <span class="text-<?php echo ($office_campaign->bureaudirectory_status->redirect_count > 5) ? 'danger' : 'warning' ?>">
                                    <?php echo $office_campaign->bureaudirectory_status->redirect_count . ' redirects'; ?>
                                                            </span>
                                    <?php if ($office_campaign->bureaudirectory_status->redirect_count > 5): ?>
                                                                <span style="color:#ccc"> (stops tracking after 6)</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr class="<?php echo $status_color; ?>">
                            <th>HTTP Status</th>
                            <td>
                                <span class="text-<?php echo $status_color; ?>">
                                    <?php echo $office_campaign->bureaudirectory_status->http_code ?>
                                </span>
                            </td>
                        </tr>

                        <tr class="<?php echo $mime_color; ?>">
                            <th>Content Type</th>
                            <td>
                                <span class="text-<?php echo $mime_color; ?>">
                                    <?php echo $office_campaign->bureaudirectory_status->content_type ?>
                                </span>
                            </td>
                        </tr>

                        <?php
                        // TO DO - when we have agency files at a valid url, put the checks back
                        if(property_exists($office_campaign->bureaudirectory_status, "valid_json")) {
                           $valid_json = $office_campaign->bureaudirectory_status->valid_json;
                        }
                        else if ($http_code == 200 && $bureau_directory = curl_from_json($office_campaign->bureaudirectory_status->url, false, true)) {
                            $valid_json = true;
                        } else {
                            $valid_json = false;
                        }
                        ?>

                        <tr class="<?php echo ($valid_json == true) ? 'success' : 'danger' ?>">
                            <th>Valid JSON</th>
                            <td>
                                <span class="text-<?php echo ($valid_json == true) ? 'success' : 'danger' ?>">
                                <?php
                                if ($valid_json == true)
                                    echo 'Valid';
                                if (($valid_json == false && $valid_json !== null) || ($office_campaign->bureaudirectory_status->http_code == 200 && $valid_json != true))
                                    echo 'Invalid <span><a href="http://jsonlint.com/">Check a JSON Validator</a></span>';
                                ?>
                            </td>
                        </tr>

                        <?php if (!empty($office_campaign->bureaudirectory_status->filetime) && $office_campaign->bureaudirectory_status->filetime > 0): ?>
                            <tr>
                                <th>Last modified</th>
                                <td>
                                    <span>
                                        <?php echo date("l, d-M-Y H:i:s T", $office_campaign->bureaudirectory_status->filetime) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if (!empty($office_campaign->bureaudirectory_status->last_crawl)): ?>
                            <tr>
                                <th>Last crawl</th>
                                <td>
                                    <span>
                                        <?php echo date("l, d-M-Y H:i:s T", $office_campaign->bureaudirectory_status->last_crawl) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leadership)): ?>
                            <tr>
                                <th id="pa_bureau_it_leadership">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_bureau_it_leadership' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    Bureau IT Leadership file exists and conforms to schema?
                                </th>
                                <td>
                                    <a name="pa_bureau_it_leadership" class="anchor-point"></a>
                                    <?php echo $office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leadership ? 'Yes' : 'No';?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leaders)): ?>
                            <tr>
                                <th id="pa_bureau_it_leaders">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_bureau_it_leaders' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    # Bureau IT Leaders
                                </th>
                                <td>
                                    <a name="pa_bureau_it_leaders" class="anchor-point"></a>
                                    <?php echo $office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leaders;?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->bureaudirectory_status->tracker_fields->pa_key_bureau_it_leaders)): ?>
                            <tr>
                                <th id="pa_key_bureau_it_leaders">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_key_bureau_it_leaders' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    # Key Bureau IT Leaders
                                </th>
                                <td>
                                    <a name="pa_key_bureau_it_leaders" class="anchor-point"></a>
                                    <?php echo $office_campaign->bureaudirectory_status->tracker_fields->pa_key_bureau_it_leaders;?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->bureaudirectory_status->tracker_fields->pa_political_appointees)): ?>
                            <tr>
                                <th id="pa_political_appointees">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_political_appointees' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    # Political Appointees
                                </th>
                                <td>
                                    <a name="pa_political_appointees" class="anchor-point"></a>
                                    <?php echo $office_campaign->bureaudirectory_status->tracker_fields->pa_political_appointees;?>/<?php echo $office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leaders;?>
                                    (<?php echo intval($office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leaders) > 0 ? intval($office_campaign->bureaudirectory_status->tracker_fields->pa_political_appointees / $office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leaders * 100) : 0;?>%)
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leadership_link)): ?>
                            <tr>
                                <th id="pa_bureau_it_leadership_link">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_bureau_it_leadership_link' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    Link to Bureau IT Leadership directory
                                </th>
                                <td>
                                    <a name="pa_bureau_it_leadership_link" class="anchor-point"></a>
                                    <a href="<?php echo $office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leadership_link;?>">
                                        <?php echo $office_campaign->bureaudirectory_status->tracker_fields->pa_bureau_it_leadership_link;?>
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>

                    </table>
                </div>

            <?php endif; ?>

            <?php if (!empty($office_campaign->governanceboard_status)): ?>

                <div id="governanceboard-heading" class="panel panel-default">
                    <div class="panel-heading">
                        /digitalstrategy/governanceboards.json
                        <a class="info-icon" href="<?php echo site_url('docs') . '#governanceboard' ?>">
                            <span class="glyphicon glyphicon-info-sign"></span>
                        </a>
                    </div>

                    <table class="table table-striped table-hover">

                        <tr>
                            <th>Expected governanceboards.json URL</th>
                            <td>
                                <?php if (!empty($office_campaign->governanceboard_status->expected_url)): ?>
                                    <a href="<?php echo $office_campaign->governanceboard_status->expected_url ?>"><?php echo $office_campaign->governanceboard_status->expected_url ?></a>
                                <?php endif; ?>

                                <?php
                                $http_code = (!empty($office_campaign->governanceboard_status->http_code)) ? $office_campaign->governanceboard_status->http_code : 0;

                                switch ($http_code) {
                                    case 404:
                                        $status_color = 'danger';
                                        break;
                                    case 200:
                                        $status_color = 'success';
                                        break;
                                    case 0:
                                        $status_color = '';
                                        break;
                                    default:
                                        $status_color = 'warning';
                                }

                                if (!empty($office_campaign->governanceboard_status->content_type)) {
                                    if (strpos($office_campaign->governanceboard_status->content_type, 'application/json') !== false) {
                                        $mime_color = 'success';
                                    } else {
                                        $mime_color = 'danger';
                                    }
                                } else {
                                    $mime_color = 'danger';
                                }
                                ?>

                            </td>
                        </tr>

                        <tr>
                            <th>Resolved governanceboards.json URL</th>
                            <td>
                                <a href="<?php echo $office_campaign->governanceboard_status->url ?>"><?php echo $office_campaign->governanceboard_status->url ?></a>
                            </td>
                        </tr>

                        <tr>
                            <th>Redirects</th>
                            <td>
                                <?php if (!empty($office_campaign->governanceboard_status->redirect_count)): ?>
                                                            <span class="text-<?php echo ($office_campaign->governanceboard_status->redirect_count > 5) ? 'danger' : 'warning' ?>">
                                    <?php echo $office_campaign->governanceboard_status->redirect_count . ' redirects'; ?>
                                                            </span>
                                    <?php if ($office_campaign->governanceboard_status->redirect_count > 5): ?>
                                                                <span style="color:#ccc"> (stops tracking after 6)</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr class="<?php echo $status_color; ?>">
                            <th>HTTP Status</th>
                            <td>
                                <span class="text-<?php echo $status_color; ?>">
                                    <?php echo $office_campaign->governanceboard_status->http_code ?>
                                </span>
                            </td>
                        </tr>

                        <tr class="<?php echo $mime_color; ?>">
                            <th>Content Type</th>
                            <td>
                                <span class="text-<?php echo $mime_color; ?>">
                                    <?php echo $office_campaign->governanceboard_status->content_type ?>
                                </span>
                            </td>
                        </tr>

                        <?php
                        // TO DO - when we have agency files at a valid url, put the checks back
                        if(property_exists($office_campaign->governanceboard_status, "valid_json")) {
                          $valid_json = $office_campaign->governanceboard_status->valid_json;
                        }
                        else if ($http_code == 200 && $governance_board = curl_from_json($office_campaign->governanceboard_status->url, false, true)) {
                            $valid_json = true;
                        } else {
                            $valid_json = false;
                        }
                        ?>

                        <tr class="<?php echo ($valid_json == true) ? 'success' : 'danger' ?>">
                            <th>Valid JSON</th>
                            <td>
                                <span class="text-<?php echo ($valid_json == true) ? 'success' : 'danger' ?>">
                                <?php
                                if ($valid_json == true)
                                    echo 'Valid';
                                if (($valid_json == false && $valid_json !== null) || ($office_campaign->governanceboard_status->http_code == 200 && $valid_json != true))
                                    echo 'Invalid <span><a href="http://jsonlint.com/">Check a JSON Validator</a></span>';
                                ?>
                            </td>
                        </tr>

                        <?php if (!empty($office_campaign->governanceboard_status->filetime) && $office_campaign->governanceboard_status->filetime > 0): ?>
                            <tr>
                                <th>Last modified</th>
                                <td>
                                    <span>
                                        <?php echo date("l, d-M-Y H:i:s T", $office_campaign->governanceboard_status->filetime) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if (!empty($office_campaign->governanceboard_status->last_crawl)): ?>
                            <tr>
                                <th>Last crawl</th>
                                <td>
                                    <span>
                                        <?php echo date("l, d-M-Y H:i:s T", $office_campaign->governanceboard_status->last_crawl) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->governanceboard_status->tracker_fields->pa_cio_governance_board)): ?>
                            <tr>
                                <th id="pa_cio_governance_board">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_cio_governance_board' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    CIO Governance Board file exists and conforms to schema?
                                </th>
                                <td>
                                    <a name="pa_cio_governance_board" class="anchor-point"></a>
                                    <?php echo $office_campaign->governanceboard_status->tracker_fields->pa_cio_governance_board ? 'Yes' : 'No';?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->governanceboard_status->tracker_fields->pa_mapped_to_program_inventory)): ?>
                            <tr>
                                <th id="pa_mapped_to_program_inventory">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_mapped_to_program_inventory' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    % Mapped to Federal Program Inventory
                                </th>
                                <td>
                                    <a name="pa_mapped_to_program_inventory" class="anchor-point"></a>
                                    <?php echo intval($office_campaign->governanceboard_status->tracker_fields->pa_ref_program_inventory) > 0 ? intval($office_campaign->governanceboard_status->tracker_fields->pa_mapped_to_program_inventory / $office_campaign->governanceboard_status->tracker_fields->pa_ref_program_inventory * 100) : 0;?>%
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->governanceboard_status->tracker_fields->pa_cio_governance_board_link)): ?>
                            <tr>
                                <th id="pa_cio_governance_board_link">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_cio_governance_board_link' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    Link to CIO Governance Board directory
                                </th>
                                <td>
                                    <a name="pa_cio_governance_board_link" class="anchor-point"></a>
                                    <a href="<?php echo $office_campaign->governanceboard_status->tracker_fields->pa_cio_governance_board_link;?>">
                                        <?php echo $office_campaign->governanceboard_status->tracker_fields->pa_cio_governance_board_link;?>
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>

                    </table>
                </div>
            <?php endif; ?>

            <?php if (!empty($office_campaign->policyarchive_status)): ?>
            <?php
            $office_campaign->policyarchive_status = json_decode($office_campaign->policyarchive_status);
            ?>
                <div id="policyarchive-heading" class="panel panel-default">
                    <div class="panel-heading">
                        /digitalstrategy/policyarchive.zip (.tar / .tar.gz / .tgz)
                        <a class="info-icon" href="<?php echo site_url('docs') . '#policyarchive' ?>">
                            <span class="glyphicon glyphicon-info-sign"></span>
                        </a>
                    </div>

                    <table class="table table-striped table-hover">

                        <tr>
                            <th>Expected policyarchive.zip URL</th>
                            <td>
                                <?php if (!empty($office_campaign->policyarchive_status->expected_url)): ?>
                                    <a href="<?php echo $office_campaign->policyarchive_status->expected_url ?>"><?php echo $office_campaign->policyarchive_status->expected_url ?></a>
                                <?php endif; ?>

                                <?php
                                $http_code = (!empty($office_campaign->policyarchive_status->http_code)) ? $office_campaign->policyarchive_status->http_code : 0;

                                switch ($http_code) {
                                    case 404:
                                        $status_color = 'danger';
                                        break;
                                    case 200:
                                        $status_color = 'success';
                                        break;
                                    case 0:
                                        $status_color = '';
                                        break;
                                    default:
                                        $status_color = 'warning';
                                }

                                if (!empty($office_campaign->policyarchive_status->content_type)) {
                                    if (strpos($office_campaign->policyarchive_status->content_type, 'application/zip') !== false ||
                                            strpos($office_campaign->policyarchive_status->content_type, 'application/x-tar') || 
                                            strpos($office_campaign->policyarchive_status->content_type, 'application/x-gtar')
                                            ) {
                                        $mime_color = 'success';
                                    } else {
                                        $mime_color = 'danger';
                                    }
                                } else {
                                    $mime_color = 'danger';
                                }
                                ?>

                            </td>
                        </tr>

                        <tr>
                            <th>Resolved policyarchive.zip URL</th>
                            <td>
                                <a href="<?php echo $office_campaign->policyarchive_status->url ?>"><?php echo $office_campaign->policyarchive_status->url ?></a>
                            </td>
                        </tr>

                        <tr>
                            <th>Redirects</th>
                            <td>
                                <?php if (!empty($office_campaign->policyarchive_status->redirect_count)): ?>
                                                            <span class="text-<?php echo ($office_campaign->policyarchive_status->redirect_count > 5) ? 'danger' : 'warning' ?>">
                                    <?php echo $office_campaign->policyarchive_status->redirect_count . ' redirects'; ?>
                                                            </span>
                                    <?php if ($office_campaign->policyarchive_status->redirect_count > 5): ?>
                                                                <span style="color:#ccc"> (stops tracking after 6)</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr class="<?php echo $status_color; ?>">
                            <th>HTTP Status</th>
                            <td>
                                <span class="text-<?php echo $status_color; ?>">
                                    <?php echo $office_campaign->policyarchive_status->http_code ?>
                                </span>
                            </td>
                        </tr>

                        <tr class="<?php echo $mime_color; ?>">
                            <th>Content Type</th>
                            <td>
                                <span class="text-<?php echo $mime_color; ?>">
                                    <?php echo $office_campaign->policyarchive_status->content_type ?>
                                </span>
                            </td>
                        </tr>

                        <?php if (!empty($office_campaign->policyarchive_status->filetime) && $office_campaign->policyarchive_status->filetime > 0): ?>
                            <tr>
                                <th>Last modified</th>
                                <td>
                                    <span>
                                        <?php echo date("l, d-M-Y H:i:s T", $office_campaign->policyarchive_status->filetime) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if (!empty($office_campaign->policyarchive_status->last_crawl)): ?>
                            <tr>
                                <th>Last crawl</th>
                                <td>
                                    <span>
                                        <?php echo date("l, d-M-Y H:i:s T", $office_campaign->policyarchive_status->last_crawl) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->policyarchive_status->tracker_fields->pa_it_policy_archive)): ?>
                            <tr>
                                <th id="pa_it_policy_archive">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_it_policy_archive' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    IT Policy Archive file exists?
                                </th>
                                <td>
                                    <a name="pa_it_policy_archive" class="anchor-point"></a>
                                    <?php echo $office_campaign->policyarchive_status->tracker_fields->pa_it_policy_archive ? 'Yes' : 'No';?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->policyarchive_status->tracker_fields->pa_it_policy_archive_filenames)): ?>
                            <tr>
                                <th id="pa_it_policy_archive_filenames">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_it_policy_archive_filenames' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    Files in Archive
                                </th>
                                <td>
                                    <a name="pa_it_policy_archive_filenames" class="anchor-point"></a>
                                    <?php echo $office_campaign->policyarchive_status->tracker_fields->pa_it_policy_archive_filenames; ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($office_campaign->policyarchive_status->tracker_fields->pa_it_policy_archive_link)): ?>
                            <tr>
                                <th id="pa_it_policy_archive_link">
                                    <a class="info-icon" href="<?php echo site_url('docs') . '#pa_it_policy_archive_link' ?>">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </a>
                                    Link to IT Policy Archive
                                </th>
                                <td>
                                    <a name="pa_it_policy_archive_link" class="anchor-point"></a>
                                    <a href="<?php echo $office_campaign->policyarchive_status->tracker_fields->pa_it_policy_archive_link;?>">
                                        <?php echo $office_campaign->policyarchive_status->tracker_fields->pa_it_policy_archive_link;?>
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>

                    </table>
                </div>
            <?php endif; ?>



          <?php include "recommendation_detail_status.php" ?>
