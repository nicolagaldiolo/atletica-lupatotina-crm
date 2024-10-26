// Import all of CoreUI's CSS
import * as coreui from '@coreui/coreui';
window.coreui = coreui;

import "/resources/js/laravel.js";
import "/resources/js/backend-custom.js";

// Enable tooltips everywhere
const tooltipTriggerList = document.querySelectorAll('[data-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new coreui.Tooltip(tooltipTriggerEl))