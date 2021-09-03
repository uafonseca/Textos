const pdfjsLib  = require('pdfjs-dist');

const pdfjsWorker = import('pdfjs-dist/build/pdf.worker.entry');

import { customTheme } from '../plugins/theme-image-editor/default-theme-editor';

import translate from '../plugins/i18n/image-editor';

pdfjsLib.workerSrc = pdfjsWorker

const loadingTask = pdfjsLib.getDocument(url);

var pdfDoc = null,
    pagesBase64 = [],
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null,
    scale = 5,
    canvas = document.getElementById('canvas'),
    ctx = canvas.getContext('2d');

    function renderPage(num) {
        pageRendering = true;
        // Using promise to fetch the page
        pdfDoc.getPage(num).then(function(page) {
          var viewport = page.getViewport({scale: scale});
          canvas.height = viewport.height;
          canvas.width = viewport.width;
      
          // Render PDF page into canvas context
          var renderContext = {
            canvasContext: ctx,
            viewport: viewport
          };
          var renderTask = page.render(renderContext);
      
          // Wait for rendering to finish
          renderTask.promise.then(function() {
            pageRendering = false;
            if (pageNumPending !== null) {
              // New page rendering is pending
              renderPage(pageNumPending);
              pageNumPending = null;
            }
            pagesBase64[num] = {
              img : canvas.toDataURL('image/jpeg'),
              width : viewport.width,
              height : viewport.height,
            };
          });
        });
          // Update page counters
  document.getElementById('page_num').textContent = num;
}

/**
 * If another page rendering in progress, waits until the rendering is
 * finised. Otherwise, executes rendering immediately.
 */
 function queueRenderPage(num) {
    if (pageRendering) {
      pageNumPending = num;
    } else {
      renderPage(num);
    }
  }

  /**
 * Displays previous page.
 */
function onPrevPage() {
    if (pageNum <= 1) {
      return;
    }
    pageNum--;
    queueRenderPage(pageNum);
  }
  document.getElementById('prev').addEventListener('click', onPrevPage);

  /**
 * Displays next page.
 */
function onNextPage() {
    if (pageNum >= pdfDoc.numPages) {
      return;
    }
    pageNum++;
    queueRenderPage(pageNum);
  }
  document.getElementById('next').addEventListener('click', onNextPage);


  /**
 * Asynchronously downloads PDF.
 */
pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
    pdfDoc = pdfDoc_;
    document.getElementById('page_count').textContent = pdfDoc.numPages;
  
    // Initial/first page rendering
    renderPage(pageNum);
  });

  document.getElementById('editor').addEventListener('click', function(event){
  const ImageEditor = require('tui-image-editor');
  

  $('#canvas').fadeOut();

  // const blackTheme = require('black-theme.js');
  const instance = new ImageEditor(document.querySelector('#tui-image-editor'), {
    includeUI: {
      loadImage: {
        path: pagesBase64[pageNum].img,
        name: 'SampleImage',
      },
      locale: translate,
       theme: customTheme,
      // initMenu: 'filter',
      menuBarPosition: 'left',

    },
    // cssMaxWidth: pagesBase64[pageNum].width / 2,
    // cssMaxHeight: pagesBase64[pageNum].height / 2,
    selectionStyle: {
      cornerSize: 20,
      rotatingPointOffset: 70,
    },
  });
  });
