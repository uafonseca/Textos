app.unit = {
  form: {
    index: () => {
      app.plugins.initSelect2("#unitType_book");
      $("#unitType_pdf_pdfFile_file").fileinput({
        showPreview: false,
        showUpload: false,
        // elErrorContainer: '#kartik-file-errors',
        allowedFileExtensions: ["pdf"]
    });
    },
    collection: () => {
        app.plugins.initPrototype('.activity-collection',{});
    }
  },
};

$(() => {
  app.unit.form.index();
  app.unit.form.collection();
});
