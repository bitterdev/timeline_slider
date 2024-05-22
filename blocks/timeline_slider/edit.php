<?php

defined('C5_EXECUTE') or die('Access denied');

/** @var array $items */

?>

<div id="items-container"></div>

<a href="javascript:void(0);" class="btn btn-primary" id="ccm-add-item">
    <?php echo t("Add Item"); ?>
</a>

<script id="item-template" type="text/template">
    <div class="slider-item">
        <div class="form-group">
            <label for="year-<%=id%>">
                <?php echo t("Year"); ?>
            </label>

            <input type="number" value="<%=year%>" id="year-<%=id%>" name="items[<%=id%>][year]"
                   class="form-control" min="1900" max="3000" step="1"/>
        </div>

        <div class="form-group">
            <label for="fID-<%=id%>">
                <?php echo t("Image"); ?>
            </label>

            <div id="fID-<%=id%>" data-concrete-file-input="fID-<%=id%>" class="file-selector">
                <concrete-file-input
                <%=(fID !== null ? ":file-id=\"" + fID + "\"" : "")%>
                choose-text="<?php echo t("Choose File");?>"
                input-name="items[<%=id%>][fID]">
                </concrete-file-input>
            </div>
        </div>

        <div class="form-group">
            <label for="subtitle-<%=id%>">
                <?php echo t("Subtitle"); ?>
            </label>

            <input type="text" value="<%=subtitle%>" id="subtitle-<%=id%>" name="items[<%=id%>][subtitle]"
                   class="form-control"/>
        </div>

        <div class="form-group">
            <label for="title-<%=id%>">
                <?php echo t("Title"); ?>
            </label>

            <input type="text" value="<%=title%>" id="title-<%=id%>" name="items[<%=id%>][title]" class="form-control"/>
        </div>

        <div class="form-group">
            <label for="description-<%=id%>">
                <?php echo t("Description"); ?>
            </label>

            <textarea id="description-<%=id%>" name="items[<%=id%>][description]"
                      class="form-control"><%=description%></textarea>
        </div>

        <a href="javascript:void(0);" class="btn btn-danger">
            <?php echo t("Remove Item"); ?>
        </a>
    </div>
</script>

<style type="text/css">
    .slider-item {
        border: 1px solid #dadada;
        background: #f9f9f9;
        padding: 15px;
        margin-bottom: 15px;
    }
</style>

<script type="text/javascript">
    (function ($) {
        var nextInsertId = 0;
        var items = <?php echo json_encode($items);?>;

        var addItem = function (data) {
            var defaults = {
                id: nextInsertId
            };

            var combinedData = {...defaults, ...data};

            var $item = $(_.template($("#item-template").html())(combinedData));

            nextInsertId++;

            $item.find(".btn-danger").click(function () {
                $(this).parent().remove();
            });

            Concrete.Vue.activateContext('cms', function (Vue, config) {
                $item.find(".file-selector").each(function() {
                    new Vue({
                        el: this,
                        components: config.components
                    });
                });
            });

            $("#items-container").append($item);
        };

        for (var item of items) {
            addItem(item);
        }

        $("#ccm-add-item").click(function (e) {
            e.preventDefault();
            addItem({
                year: null,
                fID: null,
                subtitle: '',
                title: '',
                description: ''
            });
            return true;
        });
    })(jQuery);
</script>
