import React, { Component } from 'react';
import { connect } from 'react-redux'
import CircularProgress from 'material-ui/CircularProgress';

import TaskList from '../../components/TaskList';

import { fetchTasks, fetchTasksFailure, fetchTasksSuccess } from '../../actions';

const mapStateToProps = state => {
  return {
    tasks: state.tasks,
    loading: state.tasksLoading,
    error: state.tasksError,
    providerConfig: state.providerConfig,
    boardId: state.selectedBoard.id,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchTasks: ({ token }, boardId) => {
      dispatch(fetchTasks())
      
      fetch(`https://api.scrumboard-it.org/boards/${boardId}/tasks`, {
        headers: new Headers({
          'Authorization': `Bearer ${token}`,
        })
      }).then((response) => {
        if (response.ok) {
          response.json().then((data) => {
            dispatch(fetchTasksSuccess(data));
          })
        } else {
          response.json().then((error) => {
            dispatch(fetchTasksFailure(error));
          })
        }
      }).catch((error) => {
        dispatch(fetchTasksFailure(error));
      })
    }
  }
}

class TaskListContainer extends Component {
  componentDidMount() {
    const { fetchTasks, providerConfig, boardId } = this.props;
    fetchTasks(providerConfig, boardId);
  }
  
  render() {
    const { tasks, loading, error } = this.props;
    
    if (loading) {
      return <CircularProgress />
    } else if (error){
      return <p>{error.message}</p>
    } else {
      return <TaskList tasks={tasks} />
    }
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(TaskListContainer);