<?php

namespace Concrete\Package\TimelineSlider\Block\TimelineSlider;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Error\ErrorList\ErrorList;

class Controller extends BlockController
{
    protected $btTable = 'btTimelineSlider';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockOutputLifetime = 300;

    public function getBlockTypeDescription()
    {
        return t('Add a timeline slider to your site.');
    }

    public function getBlockTypeName()
    {
        return t("Timeline Slider");
    }

    public function view()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $items = $db->fetchAll("SELECT * FROM btTimelineSliderItems WHERE bID = ?", [$this->bID]);
        $year = array_column($items, 'year');
        array_multisort($year, SORT_ASC, $items);
        $this->set("items", $items);
        $this->set("activeTaskNumber", (int)array_pop(array_reverse($items))["year"]);
    }

    public function add()
    {
        $this->set("items", []);
        $this->set("targetPage", null);
    }

    public function edit()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $this->set("items", $db->fetchAll("SELECT * FROM btTimelineSliderItems WHERE bID = ?", [$this->bID]));
    }

    public function delete()
    {
        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $db->executeQuery("DELETE FROM btTimelineSliderItems WHERE bID = ?", [$this->bID]);

        parent::delete();
    }

    public function save($args)
    {
        parent::save($args);

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);
        $db->executeQuery("DELETE FROM btTimelineSliderItems WHERE bID = ?", [$this->bID]);

        if (is_array($args["items"])) {
            foreach ($args["items"] as $item) {
                $db->executeQuery("INSERT INTO btTimelineSliderItems (bID, `year`, fID, subtitle, title, description) VALUES (?, ?, ?, ?, ?, ?)", [
                    $this->bID,
                    isset($item["year"]) && !empty($item["year"]) ? (int)$item["year"] : 0,
                    isset($item["fID"]) && !empty($item["fID"]) ? (int)$item["fID"] : null,
                    isset($item["subtitle"]) && !empty($item["subtitle"]) ? (string)$item["subtitle"] : null,
                    isset($item["title"]) && !empty($item["title"]) ? (string)$item["title"] : null,
                    isset($item["description"]) && !empty($item["description"]) ? (string)$item["description"] : null
                ]);
            }
        }
    }

    public function validate($args)
    {
        $e = new ErrorList;

        if (isset($args["items"])) {
            foreach($args["items"] as $item) {
                if (!isset($item["year"]) || empty($item["year"]) || !is_numeric($item["year"])) {
                    $e->addError(t("You need to enter a valid year."));
                }

                if (!isset($item["fID"]) || empty($item["fID"]) || !is_numeric($item["fID"])) {
                    $e->addError(t("You need to select a valid file."));
                }

                if (!isset($item["subtitle"]) || empty($item["subtitle"])) {
                    $e->addError(t("You need to enter a valid subtitle."));
                }

                if (!isset($item["title"]) || empty($item["title"])) {
                    $e->addError(t("You need to enter a valid title."));
                }

                if (!isset($item["description"]) || empty($item["description"])) {
                    $e->addError(t("You need to enter a valid description."));
                }
            }
        } else {
            $e->addError(t("You need to add at least one item."));
        }
        
        return $e;
    }

    public function duplicate($newBID)
    {
        parent::duplicate($newBID);

        /** @var Connection $db */
        $db = $this->app->make(Connection::class);

        $copyFields = '`year`, fID, subtitle, title, description';
        
        $db->executeUpdate("INSERT INTO btTimelineSliderItems (bID, {$copyFields}) SELECT ?, {$copyFields} FROM btTimelineSliderItems WHERE bID = ?", [
                $newBID,
                $this->bID
            ]
        );
    }
}