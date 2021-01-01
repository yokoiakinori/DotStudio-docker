<template>
	<div class="userSettings">
		<h1>ユーザープロフィールの編集</h1>

		<div class="infomationItem updateThumbnail">
			<img :src="user.thumbnail" :alt="`${user.name}のサムネイル`" class="thumbnail" />
			<button @click="modalToggle">サムネイル更新</button>
			<transition>
				<modalWindow v-if="modalWindow" @closeModal="modalToggle" @formEnter="thumbnailUpdate">
					<h2>サムネイル更新</h2>
					<input type="file" @change="onFileChange" />
					<output v-if="preview" class="previewImage">
						<img :src="preview" alt />
					</output>
				</modalWindow>
			</transition>
		</div>

		<form action class="updateInformation" @submit.prevent="profileUpdate">
			<label for="update-name">新しい名前</label>
			<input type="text" :placeholder="user.name" v-model="updateForm.name" />
			<label for="update-name">新しい挨拶文</label>
			<textarea
				:placeholder="user.introduction"
				v-model="updateForm.introduction"
				cols="30"
				rows="4"
			></textarea>
			<button type="submit" class="formButton">更新する</button>
		</form>
	</div>
</template>

<script>
import ModalWindow from '../components/ModalWindow.vue';
import Axios from 'axios';
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util';
export default {
	components: {
		ModalWindow,
	},
	props: {
		id: {
			type: String,
			required: true,
		},
	},
	data() {
		return {
			modalWindow: false,
			preview: null,
			thumbnail: null,
			user: {
				name: String,
				introduction: String,
				thumbnail: String,
				followCount: Number,
				followerCount: Number,
			},
			updateForm: {
				name: '',
				introduction: '',
			},
		};
	},
	methods: {
		modalToggle() {
			this.reset();
			this.modalWindow = !this.modalWindow;
		},
		onFileChange(event) {
			// 何も選択されていなかったら処理中断
			if (event.target.files.length === 0) {
				this.reset();
				return false;
			}

			// ファイルが画像ではなかったら処理中断
			if (!event.target.files[0].type.match('image.*')) {
				this.reset();
				return false;
			}

			// FileReaderクラスのインスタンスを取得
			const reader = new FileReader();

			// ファイルを読み込み終わったタイミングで実行する処理
			reader.onload = e => {
				// previewに読み込み結果（データURL）を代入する
				// previewに値が入ると<output>につけたv-ifがtrueと判定される
				// また<output>内部の<img>のsrc属性はpreviewの値を参照しているので
				// 結果として画像が表示される
				this.preview = e.target.result;
			};

			// ファイルを読み込む
			// 読み込まれたファイルはデータURL形式で受け取れる（上記onload参照）
			reader.readAsDataURL(event.target.files[0]);
			this.thumbnail = event.target.files[0];
		},
		async profileUpdate() {
			const response = await axios.post('/api/userupdate', this.updateForm);
			if (response.status !== OK) {
				this.$store.commit('error/setCode', response.status);
				return false;
			}
			this.$store.commit('auth/updateUser', response);
		},
		reset() {
			this.preview = '';
			this.thumbnail = null;
		},
		async showUser() {
			const response = await axios.get(`/api/users/${this.id}`);
			if (response.status !== OK) {
				this.$store.commit('error/setCode', response.status);
				return false;
			}
			this.user.name = response.data[0].name;
			this.user.introduction = response.data[0].introduction;
			this.user.thumbnail = response.data[0].userthumbnail.url;
			this.user.followCount = response.data[1];
			this.user.followerCount = response.data[2];
		},
		async thumbnailUpdate() {
			const formData = new FormData();
			formData.append('userthumbnail', this.thumbnail);
			const response = await axios.post('/api/thumbnail/update', formData);
			this.modalToggle();
		},
	},
	watch: {
		$route: {
			async handler() {
				this.$store.commit('randing/loadingSwitch', true);
				await this.showUser();
				this.$store.commit('randing/loadingSwitch', false);
			},
			immediate: true,
		},
	},
};
</script>

<style lang="scss" scoped>
@import '../../sass/common.scss';
.userSettings {
	width: 90%;
	display: flex;
	flex-flow: wrap row;
	margin: 0 auto;
	padding-top: 30px;
	h1 {
		width: 100%;
		font-size: 18px;
		margin-bottom: 20px;
	}
}
.updateThumbnail {
	display: flex;
	flex-flow: column;
}
.thumbnail {
	width: 130px;
	height: 130px;
}
.updateInformation {
	display: flex;
	flex-flow: wrap column;
	margin-left: 20px;
	height: 180px;
	label {
		margin-top: 10px;
	}
	input {
		border-bottom: solid 1px $maincolor;
	}
	textarea {
		border-bottom: solid 1px $maincolor;
	}
}
.formButton {
	width: 120px;
	padding: 5px;
}
</style>
