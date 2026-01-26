
$(document).ready(function () {
    // Add Variation
    function addVariation(key) {
        let variationHTML = `<div class="js-repeater-item" data-item="${key}"><div class="mb-3 row">`;

        // Generate input fields for all languages
        languages.forEach(language => {
            variationHTML += `
        <div class="col-6">
            <label class="form-label">Variation Name (In ${language.code})</label>
            <input type="text" class="form-control" name="${language.code}_variation_${key}" />
            <input type="hidden" name="variation_helper[]" value="${key}" />
        </div>`;
        });

        variationHTML += `
    <div class="col-12 varient-btn-div">
        <button class="btn btn-danger btn-sm js-repeater-remove mb-2" type="button">
            <i class="fas fa-times"></i> Remove
        </button>
        <button class="btn btn-success btn-sm js-repeater-child-add mb-2" type="button" data-it="${key}">
            <i class="fas fa-plus"></i> Add Option
        </button>
        <div class="repeater-child-list mt-2 col-12" id="options${key}"></div>
    </div>
</div>`;

        $("#js-repeater-container").append(variationHTML);
    }

    // Add Option
    function addOption(parentKey, optionIndex) {
        let optionHTML = `<div class="repeater-child-item mb-3" id="options${parentKey}_${optionIndex}">
    <div class="row align-items-start">
`;

        // Generate input fields for all languages
        languages.forEach(language => {
            optionHTML += `
        <div class="col-3">
            <label class="form-label">Option Name (In ${language.code})</label>
            <input type="text" class="form-control" name="${language.code}_options1_${parentKey}[]" />
        </div>`;
        });

        optionHTML += `
    <div class="col-2">
        <label class="form-label">Price (${currencySymbol})</label>
        <input type="number" class="form-control" name="options2_${parentKey}[]" placeholder="0" />
    </div>
    <div class="col-2">
        <label class="form-label">Stock</label>
        <input type="number" class="form-control" name="options3_${parentKey}[]" placeholder="0" />
    </div>
    <div class="col-2">
        <button class="btn btn-danger js-repeater-child-remove btn-sm" type="button">
            <i class="fas fa-times"></i> Remove
        </button>
    </div>
</div>`;

        $(`#options${parentKey}`).append(optionHTML);
    }

    // Initialize event listeners
    let variationKey = $(".js-repeater-item").length || 0;

    $(".js-repeater-add").click(() => {
        addVariation(variationKey++);
    });

    $(document).on("click", ".js-repeater-child-add", function () {
        const parentKey = $(this).data("it");
        const optionIndex = $(`#options${parentKey} .repeater-child-item`).length;
        addOption(parentKey, optionIndex);
    });

    $(document).on("click", ".js-repeater-remove", function () {
        $(this).closest(".js-repeater-item").remove();
    });

    $(document).on("click", ".js-repeater-child-remove", function () {
        $(this).closest(".repeater-child-item").remove();
    });
});
