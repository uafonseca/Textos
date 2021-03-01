app.registration = {
  index: {
    initComponents: () => {
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
      var currentCities = [];
      
      var BATTUTA_KEY = "d1852eb18710a5342c07a673014fc9c9";
      
      url =
        "https://geo-battuta.net/api/country/all/?key=" +
        BATTUTA_KEY +
        "&callback=?";
      app.plugins.initSelect2("#user_country");
      app.plugins.initSelect2("#user_provincia");
      app.plugins.initSelect2("#user_canton");

      $("<option></option>")
      .append('--SELECCIONE--')
      .appendTo($("#user_country"));

      $("<option></option>")
      .append('--SELECCIONE--')
      .appendTo($("#user_provincia"));

      $("<option></option>")
      .append('--SELECCIONE--')
      .appendTo($("#user_canton"));

      $.getJSON(url, function (countries) {
        $.each(countries, function (key, country) {
          $("<option></option>")
            .attr("value", country.code)
            .append(country.name)
            .appendTo($("#user_country"));
        });

        $("#user_country").trigger("change");
      });

      $("#user_country").on("change", function () {
        countryCode = $("#user_country").val();
        console.log(countryCode);

        url =
          "https://geo-battuta.net/api/region/" +
          countryCode +
          "/all/?key=" +
          BATTUTA_KEY +
          "&callback=?";

        $.getJSON(url, function (regions) {
          $("#user_provincia option").remove();

          $.each(regions, function (key, region) {
            $("<option></option>")
              .attr("value", region.region)
              .append(region.region)
              .appendTo($("#user_provincia"));
          });

          $("#user_provincia").trigger("change");
        });
      });
      $("#user_provincia").on("change", function () {
        countryCode = $("#user_country").val();
        region = $("#user_provincia").val();
        url =
          "http://geo-battuta.net/api/city/" +
          countryCode +
          "/search/?region=" +
          region +
          "&key=" +
          BATTUTA_KEY +
          "&callback=?";

        $.getJSON(url, function (cities) {
          currentCities = cities;
          var i = 0;
          $("#user_canton option").remove();

          $.each(cities, function (key, city) {
            $("<option></option>")
              .attr("value", i++)
              .append(city.city)
              .appendTo($("#user_canton"));
          });
          $("#user_canton").trigger("change");
        });
      });
    },
  },
};

$(() => {
  app.registration.index.prepare();
  app.registration.index.roleActions();
  app.registration.index.initComponents();
  app.registration.index.loadAddress();
});
