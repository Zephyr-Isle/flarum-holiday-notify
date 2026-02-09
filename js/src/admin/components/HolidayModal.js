import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Stream from 'flarum/common/utils/Stream';

export default class HolidayModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);

    this.holiday = this.attrs.holiday;
    
    this.name = Stream(this.holiday ? this.holiday.name() : '');
    this.type = Stream(this.holiday ? this.holiday.type() : 'gregorian');
    this.month = Stream(this.holiday ? this.holiday.month() : 1);
    this.day = Stream(this.holiday ? this.holiday.day() : 1);
    this.template = Stream(this.holiday ? this.holiday.template() : '');
  }

  className() {
    return 'HolidayModal Modal--small';
  }

  title() {
    return this.holiday 
      ? app.translator.trans('zephyrisle-holiday-notify.admin.holidays.edit')
      : app.translator.trans('zephyrisle-holiday-notify.admin.holidays.create');
  }

  content() {
    return (
      <div className="Modal-body">
        <div className="Form-group">
          <label>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.name')}</label>
          <input className="FormControl" bidi={this.name} />
        </div>

        <div className="Form-group">
          <label>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.type')}</label>
          <select className="FormControl" bidi={this.type}>
            <option value="gregorian">Gregorian</option>
            <option value="lunar">Lunar</option>
          </select>
        </div>

        <div className="Form-group">
          <label>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.date')} (Month/Day)</label>
          <div className="HolidayModal-date">
            <input className="FormControl" type="number" min="1" max="12" bidi={this.month} placeholder="Month" />
            <input className="FormControl" type="number" min="1" max="31" bidi={this.day} placeholder="Day" />
          </div>
        </div>

        <div className="Form-group">
          <label>{app.translator.trans('zephyrisle-holiday-notify.admin.holidays.template')}</label>
          <textarea className="FormControl" bidi={this.template} placeholder="Optional custom template. Use {content} for AI generated text." />
        </div>

        <div className="Form-group">
          <Button className="Button Button--primary" type="submit" loading={this.loading}>
            {app.translator.trans('core.lib.save')}
          </Button>
        </div>
      </div>
    );
  }

  onsubmit(e) {
    e.preventDefault();
    this.loading = true;

    const data = {
      name: this.name(),
      type: this.type(),
      month: this.month(),
      day: this.day(),
      template: this.template()
    };

    if (this.holiday) {
      this.holiday.save(data).then(() => this.hide());
    } else {
      app.store.createRecord('holiday-configs').save(data).then(() => {
        this.hide();
        if (this.attrs.onsave) this.attrs.onsave();
      });
    }
  }
}
