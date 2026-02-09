import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import Page from 'flarum/common/components/Page';

import '../common';

app.initializers.add('zephyrisle-holiday-notify', () => {
  extend(Page.prototype, 'oncreate', function() {
    if (window.flarumHolidayGrayMode) {
      if (!document.getElementById('gray-overlay')) {
        const overlay = document.createElement('div');
        overlay.id = 'gray-overlay';
        overlay.className = 'gray-overlay';
        document.body.appendChild(overlay);
      }
    }
  });
});
