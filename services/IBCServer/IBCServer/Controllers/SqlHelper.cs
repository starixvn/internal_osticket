using System.Data;
using System.Data.SqlClient;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Configuration;

namespace IBCServer.Controllers
{
    public class SqlHelper
    {
        private static readonly string DEFAULT_CONNECTION_STRING = ConfigurationManager.ConnectionStrings["DefaultConnectionString"].ConnectionString;

        public string ConnectionString { get; private set; }

        public SqlHelper()
        {
            ConnectionString = DEFAULT_CONNECTION_STRING;
        }

        public SqlHelper(string connectionString)
        {
            ConnectionString = connectionString;
        }

        public DataTable ExecuteStoredProcedure(string spName)
        {
            return ExecuteStoredProcedure(spName, null, null);
        }

        public DataTable ExecuteStoredProcedure(string spName, string parameterName, object parameterValue)
        {
            return ExecuteStoredProcedure(spName, new string[] { parameterName }, new object[] { parameterValue });
        }

        public DataTable ExecuteStoredProcedure(string spName, string[] parameterNames, object[] parameterValues)
        {
            DataTable ret = new DataTable();
            try
            {
                SqlConnection sc = new SqlConnection(ConnectionString);
                SqlCommand cmd = new SqlCommand(spName, sc);
                cmd.CommandType = CommandType.StoredProcedure;

                if (parameterNames != null && parameterValues != null)
                {
                    for (int i = 0; i < parameterNames.Length; i++)
                    {
                        cmd.Parameters.AddWithValue(parameterNames[i], parameterValues[i]);
                    }
                }

                SqlDataAdapter sda = new SqlDataAdapter(cmd);
                sda.Fill(ret);
            }
            catch (Exception ex)
            {
                // log exception here
#if DEBUG
                throw ex;
#endif
            }
            return ret;
        }
    }
}
