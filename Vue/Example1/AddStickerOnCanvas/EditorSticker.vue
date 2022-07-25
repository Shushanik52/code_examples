<script>
import {fabric} from 'fabric';
import PicSnippetTitleEditor from '../PicSnippetTitleEditor';
import VueCropper from 'vue-cropperjs';
import Icons from '../Icons';
import ButtonEx from 'components/shared/Button/index';
import InputEx from 'components/shared/Input/index';
import AddSnippetTags from '../elements/AddSnippetTags';
import TemplateBundlesSelect from '../elements/TemplateBundlesSelect';
import partials from './partials';
import {addToObjectMethodInFabric} from "../../../helpers/functions";
addToObjectMethodInFabric(fabric);
export default {
  name: 'pic-snippet-editor',
  props: [
    'picSnippetId',
    'sparkDeveloper',
    'parentRef'
  ],
  components: {
    CloseBlue: Icons['close-blue'],
    ArrowDown: Icons['arrow-down'],
    ButtonEx,
    InputEx,
    VueCropper,
    PicSnippetTitleEditor,
    AddSnippetTags,
    TemplateBundlesSelect,
  },
  ...partials,
}
</script>