<?php


namespace Sheel\Map\Sheets;
use \Sheel\Helpers\StringHelper;
use \Sheel\ImportSpreadsheet\Sheets\BaseSheet;
use \Sheel\ImportSpreadsheet\RelationshipManager;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class MapSheet
 * @package Sheel\ImportSpreadsheet\Sheets
 * @property RelationshipManager $_RelationshipManager
 * @property Spreadsheet $_Spreadsheet
 */
class MapCreateMapImagerySheet extends BaseSheet
{

    /*
 |--------------------------------------------------------------------------
 | Configurable Section
 |--------------------------------------------------------------------------
 |
 | The values below will be altered on a per-sheet basis.
 */

    /**
     * Specify the name of the sheet. This is case sensitive
     *
     * @var string $_sheetName
     */
    protected $_sheetName = 'Panel Router Map Imagery';

    /**
     * Specify the model in which this sheet is in relation to. The BaseSheet class will abstractly perform add/update/
     * delete command against this model.
     *
     * @var string $_model
     */
    protected $_model = 'Sheel\Map\Models\MapCreateMapImagery';

    /**
     * If set to true all values will be converted to strings. This is good if you want to use literal string for words
     * like 'false'/'true' that become converted to 0,1 when parsed.
     *
     * @var bool $_stringifyValues
     */
    protected $_stringifyValues = false;

    /**
     * This array will define which columns must contain non-falsy values. Be careful not to select columns in which a
     * falsy value is the expected input
     *
     * @var array $_requiredColumns
     */
    protected $_requiredColumns = [ 'name'];

    /**
     * This array will define which columns are checked to determine the pre-existence of a model in the database.
     *
     * @var array $_uniqueColumns
     */
    protected $_uniqueColumns = [ 'name' ];

    /**
     * Some sheets have relations to other sheets to mimic real database relations. The spreadsheet importer needs to
     * index these relations using a name and the database ID of the related model. The array below specifies which
     * values will be used to name these relations. For example, if a model called Format has many Screen models. You
     * may use the Format's 'name' attribute. This will push a name and database ID to an accessible member array. The
     * spreadsheet is then able to determine the real Format ID by name. In the screens sheet we can reference format
     * by name and create the relation.
     *
     * @var array $_storeKeysUsing
     */
    protected $_storeKeysUsing = ['name'];

    /**
     * Here is where you define whether a column needs to look up it's value (database ID) from another sheet (imported
     * earlier). For example, the 'feed' column in a sheet called 'Conditions' might relate to a
     * sheet called 'Feeds'. In this instance you would create the following;
     *
     * 'feed' => [              <-- Use the column name that needs to be replace by the database ID
     *      'Feeds' => [        <-- Use the name of the sheet that the external data will come from
     *          'feed'          <-- Use an array of columns found in this sheet to match the indexing of the remote sheet
     *      ]
     * ]
     *
     * @var array $_relationships
     */
    protected $_relationships = [];

    /**
     * Do we need to cache this sheet so that related sheet can look of their database ID from this sheet. In other
     * words, do other sheets reference this one.
     * @var bool
     */
    protected $_isPrerequisite = true;

    /*
    |
    |--------------------------------------------------------------------------*/

    /**
     * Uncomment the method below to replace default functionality with custom functionality.
     * @param $row
     * @param $action
     * @return string
     *
    protected function customRowHandler( $row, $action ) {

    // Replace with custom functionality
    return StringHelper::convertKeyValues( $row );

    }
     */

}