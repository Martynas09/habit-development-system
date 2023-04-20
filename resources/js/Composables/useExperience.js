import axios from 'axios';
import { ref } from 'vue';

const xp = ref(0);
export default () => {
  const pullXP = async () => {
    await axios.get('/getUserXp').then((response) => {
      xp.value = response.data;
    });
  };
  return {
    pullXP,
    xp,
  };
};
