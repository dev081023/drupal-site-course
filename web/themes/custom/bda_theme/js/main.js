import {MyComponent} from './Components/MyComponent'
import ReactDOM from "react-dom";

(function ($, Drupal) {
  Drupal.behaviors.customElement = {
    attach: function (context, settings) {
      $('#block-bdatemplate, .custom-react-list', context).each(function () {
        const root = ReactDOM.createRoot($(this)[0]);
        root.render(<MyComponent/>);
      });
    }
  }
})(jQuery, Drupal)
