
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
import * as zrUtil from 'zrender/lib/core/util.js';
import { getLayoutRect } from '../../util/layout.js';
var paramsSet = [['left', 'right', 'width'], ['top', 'bottom', 'height']];
/**
 * @param visualMapModel
 * @param api
 * @param itemSize always [short, long]
 * @return {string} 'left' or 'right' or 'top' or 'bottom'
 */
export function getItemAlign(visualMapModel, api, itemSize) {
  var modelOption = visualMapModel.option;
  var itemAlign = modelOption.align;
  if (itemAlign != null && itemAlign !== 'auto') {
    return itemAlign;
  }
  // Auto decision align.
  var ecSize = {
    width: api.getWidth(),
    height: api.getHeight()
  };
  var realIndex = modelOption.orient === 'horizontal' ? 1 : 0;
  var reals = paramsSet[realIndex];
  var fakeValue = [0, null, 10];
  var layoutInput = {};
  for (var i = 0; i < 3; i++) {
    layoutInput[paramsSet[1 - realIndex][i]] = fakeValue[i];
    layoutInput[reals[i]] = i === 2 ? itemSize[0] : modelOption[reals[i]];
  }
  var rParam = [['x', 'width', 3], ['y', 'height', 0]][realIndex];
  var rect = getLayoutRect(layoutInput, ecSize, modelOption.padding);
  return reals[(rect.margin[rParam[2]] || 0) + rect[rParam[0]] + rect[rParam[1]] * 0.5 < ecSize[rParam[1]] * 0.5 ? 0 : 1];
}
/**
 * Prepare dataIndex for outside usage, where dataIndex means rawIndex, and
 * dataIndexInside means filtered index.
 */
// TODO: TYPE more specified payload types.
export function makeHighDownBatch(batch, visualMapModel) {
  zrUtil.each(batch || [], function (batchItem) {
    if (batchItem.dataIndex != null) {
      batchItem.dataIndexInside = batchItem.dataIndex;
      batchItem.dataIndex = null;
    }
    batchItem.highlightKey = 'visualMap' + (visualMapModel ? visualMapModel.componentIndex : '');
  });
  return batch;
}