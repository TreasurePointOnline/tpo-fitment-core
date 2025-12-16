jQuery(document).ready(function($) {
    const $year = $('#tpo-year');
    const $make = $('#tpo-make');
    const $model = $('#tpo-model');
    const $goBtn = $('#tpo-go-btn');

    // 1. Load Years on Init
    $.ajax({
        url: tpo_ajax.url,
        type: 'POST',
        data: {
            action: 'tpo_get_years',
            nonce: tpo_ajax.nonce
        },
        success: function(response) {
            if(response.success) {
                $.each(response.data, function(index, value) {
                    $year.append('<option value="' + value + '">' + value + '</option>');
                });
            }
        }
    });

    // 2. Year Change -> Load Makes
    $year.on('change', function() {
        const yearVal = $(this).val();
        
        // Reset subsequent fields
        $make.html('<option value="">Make</option>').prop('disabled', true);
        $model.html('<option value="">Model</option>').prop('disabled', true);
        $goBtn.prop('disabled', true);

        if(yearVal) {
            $make.prop('disabled', true).html('<option>Loading...</option>'); // Show loading
            
            $.ajax({
                url: tpo_ajax.url,
                type: 'POST',
                data: {
                    action: 'tpo_get_makes',
                    year: yearVal,
                    nonce: tpo_ajax.nonce
                },
                success: function(response) {
                    $make.html('<option value="">Make</option>'); // Clear loading
                    if(response.success) {
                        $make.prop('disabled', false);
                        $.each(response.data, function(index, item) {
                            $make.append('<option value="' + item.make_id + '">' + item.make_name + '</option>');
                        });
                    }
                }
            });
        }
    });

    // 3. Make Change -> Load Models
    $make.on('change', function() {
        const makeId = $(this).val();
        const yearVal = $year.val();

        $model.html('<option value="">Model</option>').prop('disabled', true);
        $goBtn.prop('disabled', true);

        if(makeId) {
            $model.prop('disabled', true).html('<option>Loading...</option>');

            $.ajax({
                url: tpo_ajax.url,
                type: 'POST',
                data: {
                    action: 'tpo_get_models',
                    year: yearVal,
                    make_id: makeId,
                    nonce: tpo_ajax.nonce
                },
                success: function(response) {
                    $model.html('<option value="">Model</option>');
                    if(response.success) {
                        $model.prop('disabled', false);
                        $.each(response.data, function(index, item) {
                            // We use base_vehicle_id as the value to save
                            $model.append('<option value="' + item.base_vehicle_id + '">' + item.model_name + ' ' + (item.submodel_name || '') + '</option>');
                        });
                    }
                }
            });
        }
    });

    // 4. Model Change -> Enable Go
    $model.on('change', function() {
        if($(this).val()) {
            $goBtn.prop('disabled', false);
        } else {
            $goBtn.prop('disabled', true);
        }
    });

    // 5. Go Button -> Set Vehicle
    $goBtn.on('click', function(e) {
        e.preventDefault();
        const vehicleId = $model.val();

        if(vehicleId) {
            $(this).text('Saving...');
            
            $.ajax({
                url: tpo_ajax.url,
                type: 'POST',
                data: {
                    action: 'tpo_set_vehicle',
                    vehicle_id: vehicleId,
                    nonce: tpo_ajax.nonce
                },
                success: function(response) {
                    if(response.success) {
                        // Reload the page to apply filters
                        location.reload(); 
                    } else {
                        alert('Error saving vehicle');
                    }
                }
            });
        }
    });
});