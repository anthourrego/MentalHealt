$(function(){
	jQuery.validator.setDefaults({
	  	debug: false,
	  	ignore: ":hidden:not(.ignore)",
	  	errorElement: "em",
	  	errorPlacement: function (error, element) {
				error.addClass("invalid-feedback");
				element.closest(".form-valid").append(error);
	  	},
	  	highlight: function (element, errorClass, validClass) {
				$(element).addClass("is-invalid");
				//$(element).removeClass("is-valid");
	  	},
	  	unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass("is-invalid");
				//$(element).addClass("is-valid");
	  	},
			invalidHandler: function(form, validator){
				var error = validator.numberOfInvalids();
				if (error) {
					var element = validator.errorList[0].element;
					if ($(element).is("select.select2")) {
						$(element).select2('open');
					}
				}
			}
	});

	// validation of chosen on change
	if ($(".select2").length > 0) {
		$(".select2").each(function() {
			if ($(this).attr('required') !== undefined) {
				$(this).on("change", function() {
					$(this).valid();
				});
			}
		});
	}

	$(".formValid").validate();

	$('.modalFormulario').on('hide.bs.modal', function (event) {
		$(this).find("form").each(function(pos, formulario) {
			resetForm("#" + $(formulario).attr("id"));
		})
  });
});

function resetForm(idForm){
	$(idForm)[0].reset();
	$(".select2").trigger('change');
	$(idForm).validate().resetForm();
}