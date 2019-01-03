<?php

require_once 'findparticipantsorg.civix.php';
use CRM_Findparticipantsorg_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function findparticipantsorg_civicrm_config(&$config) {
  _findparticipantsorg_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function findparticipantsorg_civicrm_xmlMenu(&$files) {
  _findparticipantsorg_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function findparticipantsorg_civicrm_install() {
  _findparticipantsorg_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function findparticipantsorg_civicrm_postInstall() {
  _findparticipantsorg_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function findparticipantsorg_civicrm_uninstall() {
  _findparticipantsorg_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function findparticipantsorg_civicrm_enable() {
  _findparticipantsorg_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function findparticipantsorg_civicrm_disable() {
  _findparticipantsorg_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function findparticipantsorg_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _findparticipantsorg_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function findparticipantsorg_civicrm_managed(&$entities) {
  _findparticipantsorg_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function findparticipantsorg_civicrm_caseTypes(&$caseTypes) {
  _findparticipantsorg_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function findparticipantsorg_civicrm_angularModules(&$angularModules) {
  _findparticipantsorg_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function findparticipantsorg_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _findparticipantsorg_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function findparticipantsorg_civicrm_entityTypes(&$entityTypes) {
  _findparticipantsorg_civix_civicrm_entityTypes($entityTypes);
}

function findparticipantsorg_civicrm_searchColumns($contextName, &$columnHeaders, &$rows, $form) {
  /*Civi::log()->debug('findparticipantsorg_civicrm_searchColumns', [
    '$contextName' => $contextName,
    '$columnHeaders' => $columnHeaders,
    '$rows' => $rows,
  ]);*/

  if ($contextName == 'event') {
    foreach ($columnHeaders as $index => $column) {
      if (!empty($column['name']) && $column['name'] == 'Participant') {
        array_splice($columnHeaders, 2, 0, [[
          'name' => 'Organization',
          'field_name' => 'current_employer',
        ]]);

        $rowsEmployer = array();
        foreach ($rows as $key => $row) {
          $org = '';
          try {
            $org = civicrm_api3('contact', 'getvalue', [
              'id' => $row['contact_id'],
              'return' => 'current_employer',
            ]);
          }
          catch (CiviCRM_API3_Exception $e) {}

          $rows[$key]['current_employer'] = $org;
          $rowsEmployer["rowid{$row['participant_id']}"] = $org;
        }

        break;
      }
    }
  }

  /*Civi::log()->debug('findparticipantsorg_civicrm_searchColumns AFTER', [
    '$columnHeaders' => $columnHeaders,
    '$rows' => $rows,
  ]);*/
}

function findparticipantsorg_civicrm_buildForm($formName, &$form) {
  //Civi::log()->debug('', ['formName' => $formName, 'form' => $form]);

  if ($formName == 'CRM_Event_Form_Search') {
    CRM_Core_Resources::singleton()->addScriptFile(CRM_Findparticipantsorg_ExtensionUtil::LONG_NAME, 'js/EventSearch.js');
  }
}
