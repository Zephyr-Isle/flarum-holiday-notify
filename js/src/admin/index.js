import app from 'flarum/admin/app';
import '../common';
import HolidaySettingsPage from './components/HolidaySettingsPage';

app.initializers.add('zephyrisle-holiday-notify', () => {
  app.extensionData
    .for('zephyrisle-holiday-notify')
    .registerPage(HolidaySettingsPage);
});
