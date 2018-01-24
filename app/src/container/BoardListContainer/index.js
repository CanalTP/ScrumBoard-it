import React, { Component } from 'react';
import { connect } from 'react-redux'
import CircularProgress from 'material-ui/CircularProgress';
import FontIcon from 'material-ui/FontIcon';

import './BoardListContainer.css';

import BoardList from '../../components/BoardList';
import PrintPreview from '../../components/PrintPreview';
import TaskListContainer from '../TaskListContainer';
import RaisedButton from 'material-ui/RaisedButton';
import {Toolbar, ToolbarGroup} from 'material-ui/Toolbar';

import { fetchBoards, fetchBoardsFailure, fetchBoardsSuccess, selectBoard, unselectBoard, removeTaskFromPool } from '../../actions';

const mapStateToProps = state => {
  return {
    boards: state.boards,
    loading: state.boardsLoading,
    error: state.boardsError,
    providerConfig: state.providerConfig,
    selectedBoard: state.selectedBoard,
    printPool: state.printPool,
  }
}

const mapDispatchToProps = dispatch => {
  return {
    fetchBoards: ({ token }) => {
      dispatch(fetchBoards())

      fetch('https://api.scrumboard-it.org/boards', {
        headers: new Headers({
          'Authorization': `Bearer ${token}`,
        })
      }).then((response) => {
        if (response.ok) {
          response.json().then((data) => {
            dispatch(fetchBoardsSuccess(data));
          })
        } else {
          response.json().then((error) => {
            dispatch(fetchBoardsFailure(error));
          })
        }
      }).catch((error) => {
        dispatch(fetchBoardsFailure(error));
      })
    },
    selectBoard: (board) => {
      dispatch(selectBoard(board))
    },
    backClick: () => {
      dispatch(unselectBoard())
    },
    removeFromPool: (task) => {
      dispatch(removeTaskFromPool(task))
    },
  }
}

class BoardListContainer extends Component {
  componentDidMount() {
    const { fetchBoards, providerConfig } = this.props;
    fetchBoards(providerConfig);
  }

  render() {
    const { boards, loading, error, selectBoard, selectedBoard, backClick, printPool, removeFromPool } = this.props;
    let content;

    if (selectedBoard) {
      content = (
        <div className="full-height">
          <TaskListContainer boardId={selectedBoard} />
        </div>
      )
    } else if (loading) {
      content = <div className="loading-screen"><CircularProgress /></div>
    } else if (error) {
      content = <p>{error.message}</p>
    } else {
      content = <BoardList boards={boards} onBoardClick={selectBoard} />
    }

    return (
      <div className="board-list-container">
        <div className="left-panel no-print">
          {content}
        </div>
        <div className="right-panel">
          <div className="toolbar no-print">
            <Toolbar>
              <ToolbarGroup firstChild={true}></ToolbarGroup>
              <ToolbarGroup>
                <RaisedButton label="Print" primary={true} onClick={() => {window.print()}} />
              </ToolbarGroup>
            </Toolbar>
          </div>
          <PrintPreview tasks={printPool} onRemove={removeFromPool} />
        </div>
      </div>
    );
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(BoardListContainer);