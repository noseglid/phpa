<?xml version="1.0" ?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"
  doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
  doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" indent="yes"/>
<xsl:template match="/root">
  <html>
    <head>
      <title>PHP Analyzer report</title>
      <link rel="stylesheet" type="text/css" href="style.css" />
      <script type="text/javascript" src="script.js"> </script>
    </head>
    <body>
      <h1>Analysis report - PHP Analyzer</h1>
      <h2>Statistics</h2>
      <table>
        <tr>
          <td>Number of files examined</td>
          <td><xsl:value-of select="count/files" /></td>
        </tr>
        <tr>
          <td>Number of units</td>
          <td><xsl:value-of select="count/units" /></td>
        </tr>
        <tr>
          <td>Total unit SLOC</td>
          <td><xsl:value-of select="sum(units/data/sloc)"/></td>
        </tr>
        <tr>
          <td>Average SLOC/unit</td>
          <td><xsl:value-of select="round(sum(units/data/sloc) div count/units)"/></td>
        </tr>
        <tr>
          <td>Average complexity</td>
          <td><xsl:value-of select="round(sum(units/data/complexity) div count/units)"/></td>
        </tr>
        <tr>
          <td>Mean SLOC between complexity</td>
          <td><xsl:value-of select="round(sum(units/data/sloc) div sum(units/data/complexity))" /></td>
        </tr>
        <tr>
          <xsl:if test="sum(units/data/err) > 0">
            <xsl:attribute name="class">
              error
            </xsl:attribute>
          </xsl:if>
          <td>Unparsable units</td>
          <td><xsl:value-of select="sum(units/data/err)" /></td>
        </tr>
        <tr>
          <xsl:if test="sum(units/data/wrn) > 0">
            <xsl:attribute name="class">
              warning
            </xsl:attribute>
          </xsl:if>
          <td>Warnings</td>
          <td><xsl:value-of select="sum(units/data/wrn)" /></td>
        </tr>
      </table>
      <h2>Units</h2>
      <xsl:if test="sum(units/data/err) > 0">
        <p><div class="error"></div>Red lines had errors while parsing. Data may not be reliable.</p>
      </xsl:if>
      <xsl:if test="sum(units/data/wrn) > 0">
        <p><div class="warn"></div>Yellow lines had warnings while parsing (Duplicates). Frequency column may not be reliable.</p>
      </xsl:if>
      <table class="units">
        <tr>
          <th>Unit name</th>
          <th>File</th>
          <th>Row</th>
          <th>Frequency</th>
          <th>Complexity</th>
          <th>Dependencies<br />(int / ext)</th>
          <th>SLOC</th>
        </tr>
        <xsl:for-each select="units/data">
          <xsl:sort select="frequency" order="descending" data-type="number" />
          <xsl:sort select="complexity" order="descending" data-type="number" />
          <xsl:sort select="dependency" order="descending" data-type="text" />
          <tr>
            <xsl:attribute name="onclick">
              toggle_source('<xsl:value-of select="file"/>_<xsl:value-of select="row"/>_<xsl:value-of select="fnc"/>');
            </xsl:attribute>
            <xsl:if test="wrn = 1">
              <xsl:attribute name="class">warning</xsl:attribute>
            </xsl:if>
            <xsl:if test="err = 1">
              <xsl:attribute name="class">error</xsl:attribute>
            </xsl:if>
            <xsl:if test="position() mod 2 = 0 and err != 1 and wrn != 1">
              <xsl:attribute name="class">distinguish</xsl:attribute>
            </xsl:if>
            <td><xsl:value-of select="fnc"/></td>
            <td><xsl:value-of select="file"/></td>
            <td><xsl:value-of select="row"/></td>
            <td><xsl:value-of select="frequency"/></td>
            <td><xsl:value-of select="complexity"/></td>
            <td><xsl:value-of select="dependency"/></td>
            <td><xsl:value-of select="sloc"/></td>
          </tr>
          <div class="source-viewer">
            <xsl:attribute name="onclick">
              toggle_source('<xsl:value-of select="file"/>_<xsl:value-of select="row"/>_<xsl:value-of select="fnc"/>');
            </xsl:attribute>
            <xsl:attribute name="id"><xsl:value-of select="file"/>_<xsl:value-of select="row"/>_<xsl:value-of select="fnc"/></xsl:attribute>
            <pre><xsl:value-of select="src"/></pre>
          </div>
        </xsl:for-each>
      </table>
    </body>
  </html>
</xsl:template>

</xsl:stylesheet>
