
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
import { use } from '../../extension.js';
import { install as installDataZoomSelect } from '../dataZoom/installDataZoomSelect.js';
import ToolboxModel from './ToolboxModel.js';
import ToolboxView from './ToolboxView.js';
// TODOD: REGISTER IN INSTALL
import { registerFeature } from './featureManager.js';
import SaveAsImage from './feature/SaveAsImage.js';
import MagicType from './feature/MagicType.js';
import DataView from './feature/DataView.js';
import Restore from './feature/Restore.js';
import DataZoom from './feature/DataZoom.js';
export function install(registers) {
  registers.registerComponentModel(ToolboxModel);
  registers.registerComponentView(ToolboxView);
  registerFeature('saveAsImage', SaveAsImage);
  registerFeature('magicType', MagicType);
  registerFeature('dataView', DataView);
  registerFeature('dataZoom', DataZoom);
  registerFeature('restore', Restore);
  use(installDataZoomSelect);
}
