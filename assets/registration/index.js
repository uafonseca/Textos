app.registration = {
  index: {
    initComponents: () => {
      app.plugins.initSelect2("#user_country");
      app.plugins.initSelect2("#user_provincia");
      $('#user_canton').prop('disabled',true)

      $("#user_student_brithday").datepicker({
        format: "DD/MM/YYYY",
        changeYear: true,
        yearRange: "-100:+0",
      });
    },
    prepare: () => {
      $(".student").each(function (i, item) {
        $(item).prop("required", true);
      });
      $(".profesor").each(function (i, item) {
        $(item).prop("required", false);
      });
    },
    roleActions: () => {
      let student = $("#student");
      let teacher = $("#teacher");
      $('form[name="user"] input[id="user_roles_1"]').on(
        "change",
        "",
        function (e) {
          const scope = $(this);
          if (scope.filter(":checked").val()) {
            if (student.hasClass("d-none")) $("#student").removeClass("d-none");
            if (!teacher.hasClass("d-none")) $("#teacher").addClass("d-none");

            $('html, body').animate({
                scrollTop: $("#student-link").offset().top
            }, 500);

            $(".student").each(function (i, item) {
              $(item).prop("required", true);
            });
            $(".profesor").each(function (i, item) {
              $(item).prop("required", false);
            });
          }
        }
      );

      $('form[name="user"] input[id="user_roles_2"]').on(
        "change",
        "",
        function (e) {
          const scope = $(this);
          if (scope.filter(":checked").val()) {
            if (!student.hasClass("d-none")) $("#student").addClass("d-none");
            if (teacher.hasClass("d-none")) $("#teacher").removeClass("d-none");

            $('html, body').animate({
                scrollTop: $("#profesor-link").offset().top
            }, 500);

            $(".student").each(function (i, item) {
              $(item).prop("required", false);
            });
            $(".profesor").each(function (i, item) {
              $(item).prop("required", true);
            });
          }
        }
      );
    },
    loadAddress: () => {
      $('#user_country').change(function() {
        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected sport value.
        var data = {};
        data[$(this).attr('name')] = $(this).val();
        // Submit data via AJAX to the form's action path.
        $.ajax({
          url : $form.attr('action'),
          type: $form.attr('method'),
          data : data,
          success: function(html) {
            $('#user_city').replaceWith(
                $(html).find('#user_city')
            );
            app.plugins.initSelect2('#user_city');
            if($('#user_city').val()){
              $('#user_canton').prop('disabled',false);
            }
            else{
              $('#user_canton').prop('disabled',true)
            }
            $('#user_city').on('change',function (e){
              console.log($(this).val())
              if($(this).val()){
                $('#user_canton').prop('disabled',false);
              }
              else{
                $('#user_canton').prop('disabled',true)
              }
            });
          }
        });
      });
    }

  },
};

$(() => {
  app.registration.index.prepare();
  app.registration.index.roleActions();
  app.registration.index.initComponents();
  app.registration.index.loadAddress();
});
