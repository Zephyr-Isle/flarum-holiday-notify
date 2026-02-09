import app from 'flarum/common/app';
import HolidayConfig from './models/HolidayConfig';

app.initializers.add('zephyrisle-holiday-notify-common', () => {
  app.store.models['holiday-configs'] = HolidayConfig;
});
