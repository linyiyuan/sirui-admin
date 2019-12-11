import request from '@/utils/request'
export function agentMatchingRecord(params) {
  return request({
    url:'/passport/agent_matching_record',
    method:'get',
    params:params
  })
}

export function agentMatching(params) {
  return request({
    url:'/passport/agent_matching',
    method:'get',
    params:params
  })
}

export function delAgentMatchingRecord(params) {
  return request({
    url:'/passport/del_agent_matching_record',
    method:'get',
    params:params
  })
}