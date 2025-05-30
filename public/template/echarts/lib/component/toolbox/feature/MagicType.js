
/*
* Licensed to the Apache Software Foundation (ASF) under one
* or more contributor license agreements.  See the NOTICE file
* distributed with this work for additional information
* regarding copyright ownership.  The ASF licenses this file
* to you under the Apache License, Version 2.0 (the
* "License"); you may not use this file except in compliance
* with the License.  You may obtain a copy of the License at
*
*   http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing,
* software distributed under the License is distributed on an
* "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
* KIND, either express or implied.  See the License for the
* specific language governing permissions and limitations
* under the License.
*/


/**
 * AUTO-GENERATED FILE. DO NOT MODIFY.
 */

/*
* Licensed to the Apache Software Foundation (ASF) under one
* or more contributor license agreements.  See the NOTICE file
* distributed with this work for additional information
* regarding copyright ownership.  The ASF licenses this file
* to you under the Apache License, Version 2.0 (the
* "License"); you may not use this file except in compliance
* with the License.  You may obtain a copy of the License at
*
*   http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing,
* software distributed under the License is distributed on an
* "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
* KIND, either express or implied.  See the License for the
* specific language governing permissions and limitations
* under the License.
*/
import { __extends } from "tslib";
import * as echarts from '../../../core/echarts.js';
import * as zrUtil from 'zrender/lib/core/util.js';
import { ToolboxFeature } from '../featureManager.js';
import { SINGLE_REFERRING } from '../../../util/model.js';
var INNER_STACK_KEYWORD = '__ec_magicType_stack__';
var ICON_TYPES = ['line', 'bar', 'stack'];
// stack and tiled appears in pair for the title
var TITLE_TYPES = ['line', 'bar', 'stack', 'tiled'];
var radioTypes = [['line', 'bar'], ['stack']];
var MagicType = /** @class */function (_super) {
  __extends(MagicType, _super);
  function MagicType() {
    return _super !== null && _super.apply(this, arguments) || this;
  }
  MagicType.prototype.getIcons = function () {
    var model = this.model;
    var availableIcons = model.get('icon');
    var icons = {};
    zrUtil.each(model.get('type'), function (type) {
      if (availableIcons[type]) {
        icons[type] = availableIcons[type];
      }
    });
    return icons;
  };
  MagicType.getDefaultOption = function (ecModel) {
    var defaultOption = {
      show: true,
      type: [],
      // Icon group
      icon: {
        line: 'M4.1,28.9h7.1l9.3-22l7.4,38l9.7-19.7l3,12.8h14.9M4.1,58h51.4',
        bar: 'M6.7,22.9h10V48h-10V22.9zM24.9,13h10v35h-10V13zM43.2,2h10v46h-10V2zM3.1,58h53.7',
        // eslint-disable-next-line
        stack: 'M8.2,38.4l-8.4,4.1l30.6,15.3L60,42.5l-8.1-4.1l-21.5,11L8.2,38.4z M51.9,30l-8.1,4.2l-13.4,6.9l-13.9-6.9L8.2,30l-8.4,4.2l8.4,4.2l22.2,11l21.5-11l8.1-4.2L51.9,30z M51.9,21.7l-8.1,4.2L35.7,30l-5.3,2.8L24.9,30l-8.4-4.1l-8.3-4.2l-8.4,4.2L8.2,30l8.3,4.2l13.9,6.9l13.4-6.9l8.1-4.2l8.1-4.1L51.9,21.7zM30.4,2.2L-0.2,17.5l8.4,4.1l8.3,4.2l8.4,4.2l5.5,2.7l5.3-2.7l8.1-4.2l8.1-4.2l8.1-4.1L30.4,2.2z' // jshint ignore:line
      },
      // `line`, `bar`, `stack`, `tiled`
      title: ecModel.getLocaleModel().get(['toolbox', 'magicType', 'title']),
      option: {},
      seriesIndex: {}
    };
    return defaultOption;
  };
  MagicType.prototype.onclick = function (ecModel, api, type) {
    var model = this.model;
    var seriesIndex = model.get(['seriesIndex', type]);
    // Not supported magicType
    if (!seriesOptGenreator[type]) {
      return;
    }
    var newOption = {
      series: []
    };
    var generateNewSeriesTypes = function (seriesModel) {
      var seriesType = seriesModel.subType;
      var seriesId = seriesModel.id;
      var newSeriesOpt = seriesOptGenreator[type](seriesType, seriesId, seriesModel, model);
      if (newSeriesOpt) {
        // PENDING If merge original option?
        zrUtil.defaults(newSeriesOpt, seriesModel.option);
        newOption.series.push(newSeriesOpt);
      }
      // Modify boundaryGap
      var coordSys = seriesModel.coordinateSystem;
      if (coordSys && coordSys.type === 'cartesian2d' && (type === 'line' || type === 'bar')) {
        var categoryAxis = coordSys.getAxesByScale('ordinal')[0];
        if (categoryAxis) {
          var axisDim = categoryAxis.dim;
          var axisType = axisDim + 'Axis';
          var axisModel = seriesModel.getReferringComponents(axisType, SINGLE_REFERRING).models[0];
          var axisIndex = axisModel.componentIndex;
          newOption[axisType] = newOption[axisType] || [];
          for (var i = 0; i <= axisIndex; i++) {
            newOption[axisType][axisIndex] = newOption[axisType][axisIndex] || {};
          }
          newOption[axisType][axisIndex].boundaryGap = type === 'bar';
        }
      }
    };
    zrUtil.each(radioTypes, function (radio) {
      if (zrUtil.indexOf(radio, type) >= 0) {
        zrUtil.each(radio, function (item) {
          model.setIconStatus(item, 'normal');
        });
      }
    });
    model.setIconStatus(type, 'emphasis');
    ecModel.eachComponent({
      mainType: 'series',
      query: seriesIndex == null ? null : {
        seriesIndex: seriesIndex
      }
    }, generateNewSeriesTypes);
    var newTitle;
    var currentType = type;
    // Change title of stack
    if (type === 'stack') {
      // use titles in model instead of ecModel
      // as stack and tiled appears in pair, just flip them
      // no need of checking stack state
      newTitle = zrUtil.merge({
        stack: model.option.title.tiled,
        tiled: model.option.title.stack
      }, model.option.title);
      if (model.get(['iconStatus', type]) !== 'emphasis') {
        currentType = 'tiled';
      }
    }
    api.dispatchAction({
      type: 'changeMagicType',
      currentType: currentType,
      newOption: newOption,
      newTitle: newTitle,
      featureName: 'magicType'
    });
  };
  return MagicType;
}(ToolboxFeature);
var seriesOptGenreator = {
  'line': function (seriesType, seriesId, seriesModel, model) {
    if (seriesType === 'bar') {
      return zrUtil.merge({
        id: seriesId,
        type: 'line',
        // Preserve data related option
        data: seriesModel.get('data'),
        stack: seriesModel.get('stack'),
        markPoint: seriesModel.get('markPoint'),
        markLine: seriesModel.get('markLine')
      }, model.get(['option', 'line']) || {}, true);
    }
  },
  'bar': function (seriesType, seriesId, seriesModel, model) {
    if (seriesType === 'line') {
      return zrUtil.merge({
        id: seriesId,
        type: 'bar',
        // Preserve data related option
        data: seriesModel.get('data'),
        stack: seriesModel.get('stack'),
        markPoint: seriesModel.get('markPoint'),
        markLine: seriesModel.get('markLine')
      }, model.get(['option', 'bar']) || {}, true);
    }
  },
  'stack': function (seriesType, seriesId, seriesModel, model) {
    var isStack = seriesModel.get('stack') === INNER_STACK_KEYWORD;
    if (seriesType === 'line' || seriesType === 'bar') {
      model.setIconStatus('stack', isStack ? 'normal' : 'emphasis');
      return zrUtil.merge({
        id: seriesId,
        stack: isStack ? '' : INNER_STACK_KEYWORD
      }, model.get(['option', 'stack']) || {}, true);
    }
  }
};
// TODO: SELF REGISTERED.
echarts.registerAction({
  type: 'changeMagicType',
  event: 'magicTypeChanged',
  update: 'prepareAndUpdate'
}, function (payload, ecModel) {
  ecModel.mergeOption(payload.newOption);
});
export default MagicType;