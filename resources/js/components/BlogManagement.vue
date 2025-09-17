<template>
  <v-container>
    <v-card>
      <v-card-title>
        Blog Posts
        <v-spacer></v-spacer>
        <v-btn color="primary" @click="openPostDialog({})">Create Post</v-btn>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="posts"
          class="elevation-1"
        >
          <template v-slot:item.actions="{ item }">
            <v-icon small @click="openPostDialog(item)">mdi-pencil</v-icon>
            <v-icon small @click="deletePost(item.id)">mdi-delete</v-icon>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>

    <v-dialog v-model="dialog" max-width="600px">
      <v-card>
        <v-card-title>{{ formTitle }}</v-card-title>
        <v-card-text>
          <v-text-field label="Title" v-model="editedPost.title"></v-text-field>
          <v-textarea label="Content" v-model="editedPost.content.rendered"></v-textarea>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="savePost">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      posts: [],
      headers: [
        { title: 'Title', value: 'title.rendered' },
        { title: 'Content', value: 'content.rendered', sortable: false },
        { title: 'Status', value: 'status' },
        { title: 'Priority', value: 'priority' },
        { title: 'Actions', value: 'actions', sortable: false },
      ],
      dialog: false,
      editedPost: {
        title: '',
        content: { rendered: '' },
      },
      isEditing: false,
    };
  },
  computed: {
    formTitle() {
      return this.isEditing ? 'Edit Post' : 'Create Post';
    },
  },
  created() {
    this.fetchPosts();
  },
  methods: {
    async fetchPosts() {
      const token = localStorage.getItem('auth_token');
      try {
        const response = await axios.get('/posts', {
          headers: { Authorization: `Bearer ${token}` }
        });
        this.posts = response.data;
      } catch (error) {
        console.error('Failed to fetch posts', error);
      }
    },
    openPostDialog(item) {
      this.editedPost = item.id ? { ...item } : { title: '', content: { rendered: '' } };
      this.isEditing = !!item.id;
      this.dialog = true;
    },
    async savePost() {
      const token = localStorage.getItem('auth_token');
      const data = {
        title: this.editedPost.title,
        content: this.editedPost.content.rendered
      };

      try {
        if (this.isEditing) {
          await axios.put(`/posts/${this.editedPost.id}`, data, {
            headers: { Authorization: `Bearer ${token}` }
          });
        } else {
          await axios.post('/posts', data, {
            headers: { Authorization: `Bearer ${token}` }
          });
        }
        this.dialog = false;
        this.fetchPosts();
      } catch (error) {
        console.error('Failed to save post', error);
      }
    },
    async deletePost(id) {
      if (confirm('Are you sure you want to delete this post?')) {
        const token = localStorage.getItem('auth_token');
        try {
          await axios.delete(`/posts/${id}`, {
            headers: { Authorization: `Bearer ${token}` }
          });
          this.fetchPosts();
        } catch (error) {
          console.error('Failed to delete post', error);
        }
      }
    },
  },
};
</script>