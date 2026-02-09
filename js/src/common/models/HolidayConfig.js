import Model from 'flarum/common/Model';
import mixin from 'flarum/common/utils/mixin';

export default class HolidayConfig extends Model {
  name = Model.attribute('name');
  identifier = Model.attribute('identifier');
  type = Model.attribute('type');
  month = Model.attribute('month');
  day = Model.attribute('day');
  duration = Model.attribute('duration');
  isEnabled = Model.attribute('isEnabled');
  template = Model.attribute('template');
  createdAt = Model.attribute('createdAt', Model.transformDate);
  updatedAt = Model.attribute('updatedAt', Model.transformDate);
}
