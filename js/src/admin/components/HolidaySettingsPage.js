import ExtensionPage from 'flarum/admin/components/ExtensionPage';
import Button from 'flarum/common/components/Button';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import HolidayModal from './HolidayModal';

export default class HolidaySettingsPage extends ExtensionPage {
  oninit(vnode) {
    super.oninit(vnode);

    this.loading = true;
    this.holidays = [];

    this.refresh();
  }

  refresh() {
    this.loading = true;
    app.store.find('holiday-configs').then((results) => {
      this.holidays = results;
      this.loading = false;
      m.redraw();
    });
  }

  content() {
    if (this.loading) {
      return <LoadingIndicator />;
    }

    return (
      <div className="HolidaySettingsPage">
        <div className="container">
          <div className="Form-group">
            <label>{app.translator.trans('zephyrisle-holiday-notify.admin.settings.openai_section')}</label>
            <div className="helpText">{app.translator.trans('zephyrisle-holiday-notify.admin.settings.openai_help')}</div>
            
            {this.buildSettingComponent({
              type: 'text',
              setting: 'zephyrisle-holiday.openai_url',
              label: app.translator.trans('zephyrisle-holiday-notify.admin.settings.openai_url'),
              placeholder: 'https://api.openai.com/v1'
            })}
            
            {this.buildSettingComponent({
              type: 'password',
              setting: 'zephyrisle-holiday.openai_api_key',
              label: app.translator.trans('zephyrisle-holiday-notify.admin.settings.openai_api_key')
            })}

            {this.buildSettingComponent({
              type: 'text',
              setting: 'zephyrisle-holiday.openai_model',
              label: app.translator.trans('zephyrisle-holiday-notify.admin.settings.openai_model'),
              placeholder: 'gpt-3.5-turbo'
            })}
          </div>

          <hr />

          <div className="HolidaySettingsPage-header">
            <h3>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.title')}</h3>
            <Button className="Button Button--primary" onclick={() => app.modal.show(HolidayModal, { onsave: () => this.refresh() })}>
              {app.translator.trans('zephyrisle-holiday-notify.admin.holidays.create')}
            </Button>
          </div>

          <div className="HolidaySettingsPage-list">
            <table className="HolidayList">
              <thead>
                <tr>
                  <th>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.name')}</th>
                  <th>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.date')}</th>
                  <th>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.type')}</th>
                  <th>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.enabled')}</th>
                  <th>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.actions')}</th>
                </tr>
              </thead>
              <tbody>
                {this.holidays.map((holiday) => (
                  <tr>
                    <td>{holiday.name()}</td>
                    <td>{holiday.month()}/{holiday.day()}</td>
                    <td>{holiday.type()}</td>
                    <td>
                        <div className="Form-group">
                            <input type="checkbox" checked={holiday.isEnabled()} onchange={(e) => this.toggleHoliday(holiday, e.target.checked)} />
                        </div>
                    </td>
                    <td>
                      <Button className="Button Button--icon" icon="fas fa-edit" onclick={() => app.modal.show(HolidayModal, { holiday, onsave: () => this.refresh() })} />
                      <Button className="Button Button--icon" icon="fas fa-trash" onclick={() => this.deleteHoliday(holiday)} />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    );
  }

  toggleHoliday(holiday, isEnabled) {
    holiday.save({ isEnabled });
  }

  deleteHoliday(holiday) {
    if (confirm(app.translator.trans('trae-holiday-notify.admin.holidays.delete_confirm'))) {
      holiday.delete().then(() => this.refresh());
    }
  }
}
