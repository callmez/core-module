<template>
  <div>
    <q-card class="manager list-style" flat bordered>
      <div class="bg-blue-grey-1 q-pl-md q-pr-md">
        <div class="row items-center no-wrap">
          <div class="col">
            <div>媒体库</div>
          </div>

          <div class="col-auto">
            <q-btn color="green-7" round flat icon="cloud_upload" @click.stop="handleOpenUpload"></q-btn>
          </div>
        </div>
      </div>

      <q-separator />

      <q-card-section horizontal class="row">
        <q-card-section
          class="col-md-auto relative-position"
          style="width: 282px;"
          v-if="$q.screen.gt.sm"
        >
          <div v-if="selectedFile" class="file-detail absolute-top">
            <div class="file-preview">
              <q-img
                v-if="isImage(selectedFile)"
                :src="selectedFile.url"
                style="width: 282px"
                :ratio="1"
                basic
                spinner-color="white"
              >
                <template v-slot:error>
                  <q-img
                    :src="require('../../../../images/file-types/nopic.jpg')"
                    style="width: 100px"
                    :ratio="1"
                    basic
                    spinner-color="white"
                    class="rounded-borders"
                  />
                </template>
              </q-img>
              <div v-else-if="isVideo(selectedFile)">
                <q-video :ratio="1" :src="selectedFile.url" />
              </div>
              <div v-else class="type-preview">
                <img
                  :src="require(`../../../../images/file-types/${selectedFile.aggregate_type}.png`)"
                />
              </div>
            </div>
            <ul class="file-metadata">
              <li>
                名称:
                <a
                  class="text-primary"
                  :href="selectedFile.url"
                >{{selectedFile.original_basename}}</a>
                <q-icon
                  class="text-primary"
                  name="file_copy"
                  v-clipboard:copy="selectedFile.url"
                  v-clipboard:success="() => $q.notify({ message: '复制成功', position: 'top', timeout: 2000 })"
                />
              </li>
              <li>类型: {{selectedFile.mime_type}}</li>
              <li>尺寸: {{humanStorageSize(selectedFile.size)}}</li>

              <li>上传于: {{selectedFile.created_at | moment('YYYY-MM-DD HH:mm:ss')}}</li>
            </ul>
          </div>
        </q-card-section>

        <q-separator vertical v-if="$q.screen.gt.sm" />

        <div class="col">
          <q-card-section class="q-mb-xl">
            <ul class="files row">
              <li
                @click.stop="selectFile(file)"
                v-for="(file, index) in files.data"
                :key="index"
                class="file"
                :class="{'selected': isSelectedFile(file)}"
              >
                <q-img
                  v-if="isImage(file)"
                  :src="file.url"
                  style="width: 100px"
                  :ratio="1"
                  basic
                  spinner-color="white"
                  class="rounded-borders"
                >
                  <template v-slot:error>
                    <q-img
                      :src="require('../../../../images/file-types/nopic.jpg')"
                      style="width: 100px"
                      :ratio="1"
                      basic
                      spinner-color="white"
                      class="rounded-borders"
                    />
                  </template>
                </q-img>
                <div v-else class="type-preview">
                  <img :src="require(`../../../../images/file-types/${file.aggregate_type}.png`)" />
                </div>
                <p class="text-center">{{ file.original_basename }}</p>
              </li>
            </ul>
          </q-card-section>

          <div class="relative-position">
            <div class="absolute-bottom">
              <q-pagination
                class="flex flex-center"
                :value="files.current_page"
                @input="handlePage"
                :max="files.last_page"
                input
              />
            </div>
          </div>
        </div>
      </q-card-section>

      <q-inner-loading :showing="loading['media.files']">
        <q-spinner-bars size="50px" color="primary" />
      </q-inner-loading>
    </q-card>

    <q-dialog v-model="uploadDialogShow">
      <q-uploader label="上传" v-bind="uploadProps" @uploaded="handleUploaded" multiple>
        <template v-slot:list="scope">
          <div v-if="scope.files.length">
            <q-list separator>
              <q-item v-for="file in scope.files" :key="file.name">
                <q-item-section v-if="file.__img" thumbnail class="gt-xs">
                  <img :src="file.__img.src" />
                </q-item-section>

                <q-item-section>
                  <q-item-label class="full-width ellipsis">{{ file.name }}</q-item-label>

                  <q-item-label caption>Status: {{ file.__status }}</q-item-label>

                  <q-item-label caption>{{ file.__sizeLabel }} / {{ file.__progressLabel }}</q-item-label>
                </q-item-section>

                <q-item-section top side>
                  <q-btn
                    class="gt-xs"
                    size="12px"
                    flat
                    dense
                    round
                    icon="delete"
                    @click="scope.removeFile(file)"
                  />
                </q-item-section>
              </q-item>
            </q-list>
          </div>
        </template>
      </q-uploader>
    </q-dialog>
  </div>
</template>

<script>
import { mapActions, mapState, mapGetters } from "vuex";
import { format } from "quasar";
import G from "../../boot/global";

export default {
  name: "Manager",
  data() {
    return {
      uploadDialogShow: false,
      selectedID: 0
    };
  },
  created() {
    this.getFiles();
  },
  computed: {
    ...mapState(["loading"]),
    ...mapGetters("media", ["files", "types"]),
    selectedFile() {
      return this.files.data.find(file => file.id == this.selectedID);
    },
    uploadProps() {
      return {
        autoUpload: true,
        url: G.url.media.upload,
        method: "POST",
        "field-name": "file",
        headers: Object.keys(this.$http.defaults.headers.common).map(key => ({
          name: key,
          value: this.$http.defaults.headers.common[key]
        }))
      };
    }
  },
  methods: {
    ...mapActions("media", ["loadFiles"]),
    humanStorageSize: format.humanStorageSize,

    async handleOpenUpload() {
      this.uploadDialogShow = true;
    },

    async handleUploaded() {
      this.uploadDialogShow = false;
      this.handleRefresh();
    },

    async handleRefresh() {
      this.getFiles({ page: this.files.current_page });
    },

    async handlePage(page) {
      this.getFiles({ page });
    },

    async getFiles({ page = 1 } = {}) {
      this.loadFiles({ page });
    },

    selectFile(file) {
      if (this.selectedID && this.selectedID == file.id) {
        this.selectedID = 0;
      } else {
        this.selectedID = file.id;
      }
    },

    isSelectedFile(file) {
      return this.selectedID == file.id;
    },

    isImage(file) {
      return file.aggregate_type == "image";
    },

    isVideo(file) {
      return file.aggregate_type == "video";
    }
  }
};
</script>

<style lang="scss" scoped>
.manager {
  // 列表形式
  &.list-style {
    .file {
      float: left;
      margin: 6px;
      padding: 10px;

      &.selected {
        border: 1px solid #1976d2;
        padding: 9px;
      }

      & > p {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        width: 100px;
        margin: 10px 0 0 auto;
      }
    }
    .type-preview {
      width: 100px;
      height: 100px;
      text-align: center;
      line-height: 100px;
      img {
        max-height: 100px;
        max-width: 100px;
      }
    }
    .file-detail {
      .type-preview {
        width: 282px;
        height: 282px;
        line-height: 282px;
      }
      .file-metadata {
        padding: 10px 0;
        li {
          padding: 0 10px;
        }
      }
    }
  }
}
</style>
